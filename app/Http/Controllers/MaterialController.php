<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Question;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

class MaterialController extends Controller
{
    /**
     * Display a listing of materials.
     */
    public function index()
    {
        // Get actual materials
        $materials = Material::where('is_active', true)
                            ->withCount('questions')
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        
        // Get all categories
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        return view('materials.index', compact('materials', 'categories'));
    }

    /**
     * Show the form for creating a new material.
     */
    public function create()
    {
        return view('materials.create');
    }

    /**
     * Store a newly created material in storage.
     */
    public function store(Request $request)
    {
        try {
            // Validasi sederhana dengan pengecekan file terlebih dahulu
            if (!$request->hasFile('pdf_file')) {
                throw new \Exception("Tidak ada file yang diupload.");
            }
            
            $file = $request->file('pdf_file');
            
            // Check file upload errors
            if (!$file->isValid()) {
                $errorCode = $file->getError();
                $errorMessage = $file->getErrorMessage();
                
                switch ($errorCode) {
                    case UPLOAD_ERR_INI_SIZE:
                        throw new \Exception('Ukuran file melebihi batas maksimal PHP (upload_max_filesize). Saat ini: ' . ini_get('upload_max_filesize'));
                    case UPLOAD_ERR_FORM_SIZE:
                        throw new \Exception('Ukuran file melebihi batas maksimal form.');
                    case UPLOAD_ERR_PARTIAL:
                        throw new \Exception('File hanya terupload sebagian. Coba upload ulang.');
                    case UPLOAD_ERR_NO_TMP_DIR:
                        throw new \Exception('Folder temporary tidak ditemukan.');
                    case UPLOAD_ERR_CANT_WRITE:
                        throw new \Exception('Gagal menulis file ke disk.');
                    default:
                        throw new \Exception('Error upload file: ' . $errorMessage);
                }
            }
            
            // Validasi dengan pengecekan MIME type yang lebih fleksibel
            $file = $request->file('pdf_file');
            $originalName = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();
            
            // Validate basic requirements first
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|max:100|not_in:-- Pilih Kategori --',
                'pdf_file' => 'required|file|max:10240', // Max 10MB
            ], [
                'title.required' => 'Judul materi harus diisi.',
                'category.required' => 'Kategori/Topik harus dipilih.',
                'category.not_in' => 'Silakan pilih kategori yang valid.',
                'pdf_file.required' => 'File PDF harus diupload.',
                'pdf_file.file' => 'Yang diupload harus berupa file.',
                'pdf_file.max' => 'Ukuran file maksimal 10MB.',
            ]);
            
            // Custom PDF validation - check extension
            if (!in_array($extension, ['pdf'])) {
                throw new \Illuminate\Validation\ValidationException(
                    \Illuminate\Support\Facades\Validator::make([], [])
                        ->errors()
                        ->add('pdf_file', "File harus berformat PDF. Ekstensi file Anda: .{$extension}")
                );
            }
            
            // Check MIME type more flexibly (PDF files can have different MIME types)
            $allowedMimeTypes = [
                'application/pdf',
                'application/x-pdf',
                'application/x-download',
                'application/octet-stream'
            ];
            
            if (!in_array($mimeType, $allowedMimeTypes)) {
                // Try to read file header to verify it's actually a PDF
                try {
                    $fileHandle = fopen($file->getRealPath(), 'rb');
                    $header = fread($fileHandle, 4);
                    fclose($fileHandle);
                    
                    if ($header !== '%PDF') {
                        Log::error('File header check failed', [
                            'expected' => '%PDF',
                            'actual' => bin2hex($header),
                            'mime_type' => $mimeType
                        ]);
                        
                        throw new \Illuminate\Validation\ValidationException(
                            \Illuminate\Support\Facades\Validator::make([], [])
                                ->errors()
                                ->add('pdf_file', "File bukan PDF yang valid. MIME type: {$mimeType}")
                        );
                    }
                    
                    Log::info('File verified as PDF by header check', [
                        'mime_type' => $mimeType,
                        'header_valid' => true
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error reading file header', [
                        'error' => $e->getMessage(),
                        'mime_type' => $mimeType
                    ]);
                    
                    throw new \Illuminate\Validation\ValidationException(
                        \Illuminate\Support\Facades\Validator::make([], [])
                            ->errors()
                            ->add('pdf_file', "Tidak dapat memverifikasi file PDF. MIME type: {$mimeType}")
                    );
                }
            }

            // Generate unique filename
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Store file
            $filePath = $file->storeAs('materials', $fileName, 'public');
            
            // Create material record
            $material = Material::create([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category === 'Tanpa kategori spesifik' ? null : $request->category,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'is_active' => true,
            ]);

            // Return JSON untuk AJAX request
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Materi berhasil diupload!',
                    'material' => $material
                ]);
            }

            return redirect()->route('materials.show', $material)
                ->with('success', 'Materi berhasil diupload!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed in store method', [
                'errors' => $e->errors(),
                'validator_errors' => $e->validator->errors()->toArray(),
                'has_file' => $request->hasFile('pdf_file'),
                'file_details' => $request->hasFile('pdf_file') ? [
                    'name' => $request->file('pdf_file')->getClientOriginalName(),
                    'size' => $request->file('pdf_file')->getSize(),
                    'size_mb' => round($request->file('pdf_file')->getSize() / 1024 / 1024, 2),
                    'mime' => $request->file('pdf_file')->getMimeType(),
                    'extension' => $request->file('pdf_file')->getClientOriginalExtension(),
                    'is_valid' => $request->file('pdf_file')->isValid(),
                    'error_code' => $request->file('pdf_file')->getError()
                ] : 'no_file_uploaded'
            ]);
            
            if ($request->wantsJson()) {
                // Get first specific error message
                $firstError = collect($e->errors())->flatten()->first();
                
                return response()->json([
                    'success' => false,
                    'message' => $firstError,
                    'errors' => $e->errors(),
                    'debug' => [
                        'has_file' => $request->hasFile('pdf_file'),
                        'php_limits' => [
                            'upload_max_filesize' => ini_get('upload_max_filesize'),
                            'post_max_size' => ini_get('post_max_size')
                        ]
                    ]
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
            
        } catch (\Exception $e) {
            Log::error('Material upload error: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat upload: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat upload: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified material.
     */
    public function show($id)
    {
        $material = Material::with('questions')->findOrFail($id);
        return view('materials.show', compact('material'));
    }

    /**
     * Show the form for editing the specified material.
     */
    public function edit($id)
    {
        $material = Material::findOrFail($id);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('materials.edit', compact('material', 'categories'));
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, $id)
    {
        // Enhanced validation with detailed error messages
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'required|string|max:100|not_in:-- Pilih Kategori --',
                'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
            ], [
                'title.required' => 'Judul materi harus diisi.',
                'category.required' => 'Kategori/Topik harus dipilih.',
                'category.not_in' => 'Silakan pilih kategori yang valid.',
                'pdf_file.mimes' => 'File yang diupload harus berformat PDF.',
                'pdf_file.max' => 'Ukuran file maksimal 10MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for material update', [
                'errors' => $e->errors(),
                'request_data' => $request->except(['pdf_file']),
                'has_file' => $request->hasFile('pdf_file')
            ]);
            throw $e;
        }

        $material = Material::findOrFail($id);
        
        $fileUpdated = false;
        
        // Handle file upload if new file is provided
        if ($request->hasFile('pdf_file') && $request->file('pdf_file')->isValid()) {
            try {
                // Delete old file
                if (Storage::disk('public')->exists($material->file_path)) {
                    Storage::disk('public')->delete($material->file_path);
                }
                
                $uploadedFile = $request->file('pdf_file');
                $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
                
                // Ensure materials directory exists
                if (!Storage::disk('public')->exists('materials')) {
                    Storage::disk('public')->makeDirectory('materials');
                }
                
                $filePath = $uploadedFile->storeAs('materials', $fileName, 'public');
                
                if ($filePath) {
                    $material->update([
                        'file_path' => $filePath,
                        'file_name' => $uploadedFile->getClientOriginalName(),
                        'file_size' => $uploadedFile->getSize(),
                    ]);
                    
                    $fileUpdated = true;
                } else {
                    return redirect()->back()->withErrors(['pdf_file' => 'Failed to store the PDF file. Please try again.']);
                }
                
            } catch (\Exception $e) {
                Log::error('Exception during file upload', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return redirect()->back()->withErrors(['pdf_file' => 'Error uploading file: ' . $e->getMessage()]);
            }
        } elseif ($request->hasFile('pdf_file') && !$request->file('pdf_file')->isValid()) {
            $file = $request->file('pdf_file');
            $errorMessage = 'File upload error: ' . $file->getErrorMessage();
            Log::error('Invalid file upload', ['error' => $errorMessage, 'error_code' => $file->getError()]);
            return redirect()->back()->withErrors(['pdf_file' => $errorMessage]);
        }

        // Handle "Tanpa kategori spesifik" conversion to null
        $category = $request->category === 'Tanpa kategori spesifik' ? null : $request->category;
        
        $material->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $category,
        ]);

        $successMessage = $fileUpdated ? 
            'Materi dan file PDF berhasil diupdate!' : 
            'Materi berhasil diupdate! (File PDF tidak diganti)';

        return redirect()->route('materials.index')->with('success', $successMessage);
    }

    /**
     * Remove the specified material from storage.
     */
    public function destroy($id)
    {
        $material = Material::findOrFail($id);
        
        // Delete file from storage
        if (Storage::disk('public')->exists($material->file_path)) {
            Storage::disk('public')->delete($material->file_path);
        }
        
        $material->delete();
        
        return redirect()->route('materials.index')->with('success', 'Materi berhasil dihapus!');
    }

    /**
     * Download the PDF file
     */
    public function download($id)
    {
        $material = Material::findOrFail($id);
        
        if (Storage::disk('public')->exists($material->file_path)) {
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $material->file_name . '"',
                'Content-Security-Policy' => 'default-src \'self\'',
                'X-Content-Type-Options' => 'nosniff',
                'X-Frame-Options' => 'DENY',
                'X-XSS-Protection' => '1; mode=block',
                'Cache-Control' => 'private, no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ];
            
            return response()->download(
                storage_path('app/public/' . $material->file_path), 
                $material->file_name,
                $headers
            );
        }
        
        return redirect()->back()->with('error', 'File tidak ditemukan!');
    }

    /**
     * API Download endpoint for n8n (no authentication required)
     */
    public function apiDownload($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            $filePath = storage_path('app/public/' . $material->file_path);
            
            if (!file_exists($filePath)) {
                Log::error('File not found for API download', [
                    'material_id' => $id,
                    'file_path' => $filePath,
                    'expected_path' => $material->file_path
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                    'debug' => [
                        'material_id' => $id,
                        'file_path' => $material->file_path,
                        'full_path' => $filePath,
                        'exists' => false
                    ]
                ], 404);
            }
            
            // Log the download attempt for security/tracking
            Log::info('API Download accessed', [
                'material_id' => $id,
                'file_name' => $material->file_name,
                'file_size' => $material->file_size,
                'user_agent' => request()->userAgent(),
                'ip' => request()->ip(),
                'timestamp' => now()->toISOString()
            ]);
            
            // Return file with proper headers for download
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="' . $material->file_name . '"',
                'X-Material-ID' => $material->id,
                'X-Material-Title' => $material->title,
                'X-Material-Category' => $material->category ?? 'uncategorized',
                'X-File-Size' => $material->file_size,
                'Cache-Control' => 'no-cache, must-revalidate',
                'Expires' => 'Mon, 26 Jul 1997 05:00:00 GMT'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in API download', [
                'material_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to download file: ' . $e->getMessage(),
                'debug' => [
                    'material_id' => $id,
                    'error_line' => $e->getLine(),
                    'error_file' => $e->getFile()
                ]
            ], 500);
        }
    }

    /**
     * Download questions for specific material as Excel (.xlsx format)
     * Minimal Excel format for maximum compatibility
     */
    public function downloadQuestionsExcel($id)
    {
        $material = Material::findOrFail($id);
        $questions = Question::where('material_id', $id)->get();

        if ($questions->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada soal untuk materi ini!');
        }

        try {
            // Create minimal Excel without any extra properties
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Remove default properties
            $spreadsheet->getProperties()->setCreator('');
            $spreadsheet->getProperties()->setTitle('');
            $spreadsheet->getProperties()->setDescription('');
            $spreadsheet->getProperties()->setSubject('');
            $spreadsheet->getProperties()->setKeywords('');
            $spreadsheet->getProperties()->setCategory('');
            
            // Set headers with bold formatting
            $sheet->getCell('A1')->setValueExplicit('Teks Soal', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('B1')->setValueExplicit('Tipe Soal', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('C1')->setValueExplicit('Jawaban', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('D1')->setValueExplicit('Opsi A', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('E1')->setValueExplicit('Opsi B', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('F1')->setValueExplicit('Opsi C', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('G1')->setValueExplicit('Opsi D', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('H1')->setValueExplicit('Opsi E', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('I1')->setValueExplicit('Tingkat kesulitan', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            
            // Apply bold formatting to header row
            $sheet->getStyle('A1:I1')->getFont()->setBold(true);
            
            // Add data rows
            $row = 2;
            foreach ($questions as $question) {
                // Get raw data without any processing
                $questionText = $question->question ?? '';
                $questionType = $question->tipe_soal ?? 'pilihan_ganda';
                $answer = $question->answer ?? 'A';
                $optionA = $question->option_a ?? '';
                $optionB = $question->option_b ?? '';
                $optionC = $question->option_c ?? '';
                $optionD = $question->option_d ?? '';
                $optionE = $question->option_e ?? '';
                $difficulty = $question->difficulty ?? '';
                
                // Set as strings explicitly
                $sheet->getCell('A' . $row)->setValueExplicit($questionText, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('B' . $row)->setValueExplicit($questionType, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('C' . $row)->setValueExplicit($answer, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('D' . $row)->setValueExplicit($optionA, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('E' . $row)->setValueExplicit($optionB, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('F' . $row)->setValueExplicit($optionC, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('G' . $row)->setValueExplicit($optionD, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('H' . $row)->setValueExplicit($optionE, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('I' . $row)->setValueExplicit($difficulty, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                
                $row++;
            }
            
            // Generate filename with material name and category
            $materialName = Str::slug($material->title);
            $categoryName = Str::slug($material->category);
            $filename = $materialName . '_' . $categoryName . '.xlsx';
            
            // Save with minimal writer settings
            $tempFile = tempnam(sys_get_temp_dir(), 'excel_minimal_');
            
            $writer = new Xlsx($spreadsheet);
            // Remove any extra features
            $writer->setPreCalculateFormulas(false);
            $writer->save($tempFile);
            
            // Verify file
            if (!file_exists($tempFile) || filesize($tempFile) === 0) {
                throw new \Exception('Excel file was not created properly');
            }
            
            // Return with minimal headers
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Excel export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Tidak dapat membuat file Excel: ' . $e->getMessage());
        }
    }


    /**
     * Show questions for specific material
     */
    public function questions($id)
    {
        $material = Material::findOrFail($id);
        $questions = Question::where('material_id', $id)->paginate(10);
        
        return view('materials.questions', compact('material', 'questions'));
    }

    /**
     * Notify n8n after material upload
     */
    private function notifyN8n($material)
    {
        Log::info('notifyN8n method called', [
            'material_id' => $material->id,
            'material_title' => $material->title
        ]);
        
        try {
            // URL n8n webhook - bisa dikonfigurasi di .env
            $n8nWebhookUrl = env('N8N_WEBHOOK_URL');
            
            Log::info('Using webhook URL', ['webhook_url' => $n8nWebhookUrl]);
            
            // Get file path
            $filePath = storage_path('app/public/' . $material->file_path);
            
            // Data yang dikirim ke n8n
            $data = [
                'material_id' => $material->id,
                'title' => $material->title,
                'description' => $material->description,
                'category' => $material->category,
                'file_path' => $material->file_path,
                'file_name' => $material->file_name,
                'file_size' => $material->file_size,
                'file_url' => asset('storage/' . $material->file_path),
                'download_url' => route('materials.download', $material->id),
                'api_download_url' => url('/api/public/materials/' . $material->id . '/download'),
                'api_file_content_url' => url('/api/public/materials/' . $material->id . '/file-content'),
                'api_file_stream_url' => url('/api/public/materials/' . $material->id . '/stream'),
                'created_at' => $material->created_at->toISOString(),
                'trigger_source' => 'laravel_form_upload'
            ];
            
            // Kirim file sebagai base64 untuk menghindari masalah binary dalam JSON
            if (file_exists($filePath)) {
                try {
                    // Cek ukuran file - jika terlalu besar, jangan kirim base64
                    $fileSize = filesize($filePath);
                    $maxFileSize = env('N8N_FILE_SIZE_LIMIT', 5242880); // Default 5MB
                    if ($fileSize > $maxFileSize) {
                        Log::warning('File too large for base64 encoding', [
                            'material_id' => $material->id,
                            'file_size' => $fileSize,
                            'limit' => $maxFileSize
                        ]);
                        $data['file_exists'] = true;
                        $data['file_too_large'] = true;
                        $data['file_mime_type'] = 'application/pdf';
                        $data['message'] = 'File too large for base64 encoding. Use API endpoints to access file content.';
                    } else {
                        // Baca file dengan error handling
                        $fileContent = file_get_contents($filePath);
                        if ($fileContent === false) {
                            throw new \Exception('Failed to read file contents');
                        }
                        
                        // Encode to base64 with error handling
                        $base64Content = base64_encode($fileContent);
                        if ($base64Content === false) {
                            throw new \Exception('Failed to encode file to base64');
                        }
                        
                        $data['file_content_base64'] = $base64Content;
                        $data['file_mime_type'] = 'application/pdf';
                        $data['file_exists'] = true;
                        
                        // Clear variables to free memory
                        unset($fileContent, $base64Content);
                    }
                } catch (\Exception $fileError) {
                    Log::error('Error processing file for n8n', [
                        'material_id' => $material->id,
                        'file_path' => $filePath,
                        'error' => $fileError->getMessage()
                    ]);
                    $data['file_exists'] = true;
                    $data['file_error'] = $fileError->getMessage();
                    $data['file_mime_type'] = 'application/pdf';
                    $data['message'] = 'Error processing file. Use API endpoints to access file content.';
                }
            } else {
                $data['file_exists'] = false;
                $data['error'] = 'File not found in storage';
            }

            // Log webhook attempt
            Log::info('Attempting to send webhook to n8n', [
                'material_id' => $material->id,
                'webhook_url' => $n8nWebhookUrl,
                'file_size' => $material->file_size,
                'file_exists' => file_exists($filePath),
                'has_base64' => isset($data['file_content_base64'])
            ]);

            // Kirim ke n8n menggunakan HTTP client dengan error handling untuk JSON
            try {
                $response = Http::timeout(30)->post($n8nWebhookUrl, $data);
                
                if ($response->successful()) {
                    Log::info('n8n notification sent successfully', [
                        'material_id' => $material->id,
                        'response_status' => $response->status(),
                        'response_body' => $response->body()
                    ]);
                    return true;
                } else {
                    Log::error('n8n notification failed', [
                        'material_id' => $material->id,
                        'status' => $response->status(),
                        'response' => $response->body(),
                        'webhook_url' => $n8nWebhookUrl
                    ]);
                    return false;
                }
            } catch (\Exception $httpError) {
                Log::error('HTTP error sending to n8n', [
                    'material_id' => $material->id,
                    'error' => $httpError->getMessage(),
                    'webhook_url' => $n8nWebhookUrl
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('n8n notification error', [
                'material_id' => $material->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'webhook_url' => $n8nWebhookUrl ?? 'not_set'
            ]);
            return false;
        }
    }

    /**
     * Resend existing material data to n8n
     */
    public function resendToN8n($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            // Call the same notification method
            $result = $this->notifyN8n($material);
            
            if ($result) {
                return redirect()->back()->with('success', 'Data berhasil dikirim ke n8n!');
            } else {
                return redirect()->back()->with('error', 'Gagal mengirim data ke n8n. Cek log untuk detail error.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error resending to n8n', [
                'material_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Generate questions using n8n workflow
     */
    public function generateQuestions(Request $request, $id)
    {
        // Increase PHP execution time for this request
        set_time_limit(300); // 5 minutes
        ini_set('max_execution_time', 300);
        
        try {
            $material = Material::findOrFail($id);
            
            // Clear any existing completion cache to allow new generation
            $cacheKey = "n8n_completion_material_{$material->id}";
            cache()->forget($cacheKey);
            Log::info('Cleared previous completion cache for material', ['material_id' => $material->id]);
            
            // Validate request
            $request->validate([
                'question_count' => 'required|integer|min:1|max:50',
                'difficulty' => 'required|in:mudah,menengah,sulit,campuran,easy,medium,hard,mixed',
                'auto_generate' => 'boolean'
            ]);
            
            // URL n8n webhook untuk generate questions
            $n8nGenerateUrl = env('N8N_GENERATE_QUESTIONS_URL',);
            
            // Get file path
            $filePath = storage_path('app/public/' . $material->file_path);
            
            // Prepare data for n8n
            $data = [
                'action' => 'generate_questions',
                'material_id' => $material->id,
                'material_title' => $material->title,
                'material_description' => $material->description,
                'material_category' => $material->category,
                'file_name' => $material->file_name,
                'file_size' => $material->file_size,
                'api_download_url' => 'http://' . env('DOCKER_HOST_ADDRESS', 'host.docker.internal') . ':8000/api/public/materials/' . $material->id . '/download',
                'generation_settings' => [
                    'question_count' => (int) $request->question_count,
                    'difficulty' => $request->difficulty,
                    'auto_generate' => $request->boolean('auto_generate', true),
                    'language' => 'id', // Indonesian
                    'format' => 'multiple_choice',
                    'include_explanation' => true
                ],
                'trigger_source' => 'laravel_generate_button',
                'timestamp' => now()->toISOString(),
                'callback_url' => 'http://' . env('DOCKER_HOST_ADDRESS', 'host.docker.internal') . ':8000/materials/' . $material->id
            ];
            
            // Add file content if file exists and not too large
            if (file_exists($filePath)) {
                $fileSize = filesize($filePath);
                if ($fileSize <= 5 * 1024 * 1024) { // 5MB limit
                    try {
                        $fileContent = file_get_contents($filePath);
                        $base64Content = base64_encode($fileContent);
                        $data['file_content_base64'] = $base64Content;
                        $data['file_included'] = true;
                    } catch (\Exception $e) {
                        Log::warning('Could not include file content in generation request', [
                            'material_id' => $material->id,
                            'error' => $e->getMessage()
                        ]);
                        $data['file_included'] = false;
                        $data['file_too_large_or_error'] = true;
                    }
                } else {
                    $data['file_included'] = false;
                    $data['file_too_large'] = true;
                    $data['file_size_mb'] = round($fileSize / 1024 / 1024, 2);
                }
            } else {
                $data['file_included'] = false;
                $data['file_not_found'] = true;
            }
            
            
            // Send to n8n with increased timeout and better error handling
            // Use async request to prevent PHP timeout
            $response = Http::timeout(30) // Reduce to 30 seconds for faster response
                ->retry(1, 100) // Only retry once
                ->withOptions([
                    'verify' => false, // Disable SSL verification for localhost
                    'allow_redirects' => true,
                    'http_errors' => false // Don't throw exceptions for HTTP error status codes
                ])
                ->post($n8nGenerateUrl, $data);
            
            if ($response->successful()) {
                Log::info('n8n question generation triggered successfully', [
                    'material_id' => $material->id,
                    'response_status' => $response->status(),
                    'response_body' => $response->body()
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Question generation started successfully',
                    'data' => [
                        'material_id' => $material->id,
                        'question_count' => $request->question_count,
                        'difficulty' => $request->difficulty,
                        'estimated_completion' => '1-3 minutes',
                        'n8n_response' => $response->json()
                    ]
                ]);
            } else {
                Log::error('n8n question generation failed', [
                    'material_id' => $material->id,
                    'response_status' => $response->status(),
                    'response_body' => $response->body(),
                    'response_headers' => $response->headers(),
                    'webhook_url' => $n8nGenerateUrl,
                    'request_data_size' => strlen(json_encode($data))
                ]);
                
                // More detailed error message
                $errorMessage = 'n8n webhook failed';
                if ($response->status() === 404) {
                    $errorMessage = 'n8n webhook endpoint not found (404). Please check if the workflow exists.';
                } elseif ($response->status() === 500) {
                    $errorMessage = 'n8n internal server error (500). Check n8n logs.';
                } elseif ($response->status() === 0) {
                    $errorMessage = 'Cannot connect to n8n. Is n8n running on port 5678?';
                } else {
                    $errorMessage = 'n8n returned status ' . $response->status() . ': ' . $response->body();
                }
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to trigger question generation',
                    'error' => $errorMessage,
                    'debug_info' => [
                        'status' => $response->status(),
                        'response' => $response->body(),
                        'url' => $n8nGenerateUrl
                    ]
                ], 500);
            }
            
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::warning('Connection timeout when triggering question generation - continuing with polling', [
                'material_id' => $id,
                'error' => $e->getMessage(),
                'webhook_url' => $n8nGenerateUrl ?? 'not_set',
                'error_type' => 'connection_timeout'
            ]);
            
            // Return success anyway since we have polling to detect completion
            // The request might have reached n8n even if we didn't get a response
            return response()->json([
                'success' => true,
                'message' => 'Question generation request sent (connection timeout occurred)',
                'note' => 'Request may still be processing in background. Polling will detect completion.',
                'data' => [
                    'material_id' => $id,
                    'question_count' => $request->question_count ?? 10,
                    'difficulty' => $request->difficulty ?? 'medium',
                    'estimated_completion' => '3-10 minutes',
                    'timeout_occurred' => true
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error triggering question generation', [
                'material_id' => $id,
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error triggering question generation: ' . $e->getMessage(),
                'error_type' => get_class($e)
            ], 500);
        }
    }

    /**
     * Alternative method to trigger n8n generation with fire-and-forget approach
     */
    public function generateQuestionsAsync(Request $request, $id)
    {
        try {
            $material = Material::findOrFail($id);
            
            // Clear any existing completion cache to allow new generation
            $cacheKey = "n8n_completion_material_{$material->id}";
            cache()->forget($cacheKey);
            Log::info('Cleared previous completion cache for async generation', ['material_id' => $material->id]);
            
            // Validate request
            $request->validate([
                'question_count' => 'required|integer|min:1|max:50',
                'difficulty' => 'required|in:mudah,menengah,sulit,campuran,easy,medium,hard,mixed',
                'auto_generate' => 'boolean'
            ]);
            
            // Prepare complete data for n8n trigger (keeping all important fields)
            $quickData = [
                'action' => 'generate_questions',
                'material_id' => $material->id,
                'material_title' => $material->title,
                'material_description' => $material->description,
                'material_category' => $material->category,
                'file_name' => $material->file_name,
                'file_size' => $material->file_size,
                'file_url' => asset('storage/' . $material->file_path),
                'download_url' => route('materials.download', $material->id),
                'api_download_url' => 'http://' . env('DOCKER_HOST_ADDRESS', 'host.docker.internal') . ':8000/api/public/materials/' . $material->id . '/download',
                'api_file_content_url' => 'http://' . env('DOCKER_HOST_ADDRESS', 'host.docker.internal') . ':8000/api/public/materials/' . $material->id . '/file-content',
                'api_file_stream_url' => 'http://' . env('DOCKER_HOST_ADDRESS', 'host.docker.internal') . ':8000/api/public/materials/' . $material->id . '/stream',
                'questions_api_url' => 'http://' . env('DOCKER_HOST_ADDRESS', 'host.docker.internal') . ':8000/api/questions/array',
                'questions_webhook_url' => 'http://' . env('DOCKER_HOST_ADDRESS', 'host.docker.internal') . ':8000/api/public/questions/auto-save',
                'generation_settings' => [
                    'question_count' => (int) $request->question_count,
                    'difficulty' => $request->difficulty,
                    'auto_generate' => true,
                    'language' => 'id',
                    'format' => 'multiple_choice',
                    'include_explanation' => true
                ],
                'trigger_source' => 'laravel_generate_button_async',
                'timestamp' => now()->toISOString(),
                'callback_url' => 'http://' . env('DOCKER_HOST_ADDRESS', 'host.docker.internal') . ':8000/materials/' . $material->id
            ];
            
            $n8nGenerateUrl = env('N8N_GENERATE_QUESTIONS_URL', 'http://localhost:5678/webhook/generate-questions');
            
            Log::info('Triggering n8n async generation', [
                'material_id' => $material->id,
                'webhook_url' => $n8nGenerateUrl
            ]);
            
            // Fire-and-forget with very short timeout
            try {
                Http::timeout(5) // Only 5 seconds
                    ->withOptions(['verify' => false])
                    ->post($n8nGenerateUrl, $quickData);
            } catch (\Exception $e) {
                // Ignore timeout/connection errors for fire-and-forget
                Log::info('Fire-and-forget request completed (timeout expected)', [
                    'material_id' => $material->id,
                    'error' => $e->getMessage()
                ]);
            }
            
            // Always return success immediately
            return response()->json([
                'success' => true,
                'message' => 'Question generation request sent successfully',
                'data' => [
                    'material_id' => $material->id,
                    'question_count' => $request->question_count,
                    'difficulty' => $request->difficulty,
                    'estimated_completion' => '3-10 minutes',
                    'method' => 'async_fire_and_forget'
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in async question generation', [
                'material_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error triggering question generation: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send all existing materials to n8n
     */
    public function sendAllToN8n()
    {
        try {
            $materials = Material::all();
            $successCount = 0;
            $errorCount = 0;
            
            foreach ($materials as $material) {
                $result = $this->notifyN8n($material);
                
                if ($result) {
                    $successCount++;
                } else {
                    $errorCount++;
                }
                
                // Small delay to prevent overwhelming n8n
                usleep(500000); // 0.5 second delay
            }
            
            Log::info('Bulk send to n8n completed', [
                'total_materials' => $materials->count(),
                'success_count' => $successCount,
                'error_count' => $errorCount
            ]);
            
            return redirect()->back()->with('success', 
                "Bulk send completed! Success: {$successCount}, Errors: {$errorCount}"
            );
            
        } catch (\Exception $e) {
            Log::error('Error bulk sending to n8n', [
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Get file content for n8n processing
     */
    public function getFileForN8n($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            // Get file path
            $filePath = storage_path('app/public/' . $material->file_path);
            
            if (!file_exists($filePath)) {
                Log::error('File not found for material', [
                    'material_id' => $id,
                    'file_path' => $filePath,
                    'expected_path' => $material->file_path
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                    'debug' => [
                        'material_id' => $id,
                        'file_path' => $material->file_path,
                        'full_path' => $filePath,
                        'exists' => false
                    ]
                ], 404);
            }
            
            // Read file as base64 for n8n
            $fileContent = base64_encode(file_get_contents($filePath));
            
            return response()->json([
                'success' => true,
                'data' => [
                    'material_id' => $material->id,
                    'title' => $material->title,
                    'description' => $material->description,
                    'category' => $material->category,
                    'file_name' => $material->file_name,
                    'file_size' => $material->file_size,
                    'file_content_base64' => $fileContent,
                    'file_mime_type' => 'application/pdf',
                    'created_at' => $material->created_at->toISOString(),
                ],
                'message' => 'File content retrieved successfully'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error retrieving file for n8n', [
                'material_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve file: ' . $e->getMessage(),
                'debug' => [
                    'material_id' => $id,
                    'error_line' => $e->getLine(),
                    'error_file' => $e->getFile()
                ]
            ], 500);
        }
    }

    /**
     * Stream file for n8n processing
     */
    public function streamFileForN8n($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            $filePath = storage_path('app/public/' . $material->file_path);
            
            if (!file_exists($filePath)) {
                Log::error('File not found for streaming', [
                    'material_id' => $id,
                    'file_path' => $filePath,
                    'expected_path' => $material->file_path
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'File not found',
                    'debug' => [
                        'material_id' => $id,
                        'file_path' => $material->file_path,
                        'full_path' => $filePath,
                        'exists' => false
                    ]
                ], 404);
            }
            
            // Stream file directly
            return response()->file($filePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $material->file_name . '"',
                'X-Material-ID' => $material->id,
                'X-Material-Title' => $material->title,
                'X-Material-Category' => $material->category ?? 'uncategorized',
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error streaming file for n8n', [
                'material_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to stream file: ' . $e->getMessage(),
                'debug' => [
                    'material_id' => $id,
                    'error_line' => $e->getLine(),
                    'error_file' => $e->getFile()
                ]
            ], 500);
        }
    }
    
    /**
     * Clear generation state and cache for a material
     */
    public function clearGenerationState($id)
    {
        try {
            $material = Material::findOrFail($id);
            
            // Clear completion cache
            $cacheKey = "n8n_completion_material_{$material->id}";
            cache()->forget($cacheKey);
            
            // Get current question count for fresh baseline
            $currentQuestionCount = $material->questions()->count();
            
            Log::info('Generation state cleared', [
                'material_id' => $material->id,
                'current_questions' => $currentQuestionCount
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Generation state cleared successfully',
                'data' => [
                    'material_id' => $material->id,
                    'current_questions_count' => $currentQuestionCount,
                    'cache_cleared' => true
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error clearing generation state', [
                'material_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Error clearing generation state: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download questions from all materials in a specific category as Excel.
     */
    public function downloadCategoryQuestionsExcel($category)
    {
        try {
            // Get all materials in the category with their questions
            $materials = Material::where('category', $category)->get();
            
            if ($materials->isEmpty()) {
                return redirect()->route('materials.index')
                               ->with('error', 'Tidak ada materi ditemukan untuk kategori: ' . $category);
            }

            // Get all questions from materials in this category
            $allQuestions = Question::whereHas('material', function($query) use ($category) {
                $query->where('category', $category);
            })->with('material')->get();

            if ($allQuestions->isEmpty()) {
                return redirect()->route('materials.index')
                               ->with('error', 'Tidak ada soal ditemukan untuk kategori: ' . $category);
            }

            // Create Excel using PhpSpreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Remove default properties
            $spreadsheet->getProperties()->setCreator('');
            $spreadsheet->getProperties()->setTitle('');
            $spreadsheet->getProperties()->setDescription('');
            $spreadsheet->getProperties()->setSubject('');
            $spreadsheet->getProperties()->setKeywords('');
            $spreadsheet->getProperties()->setCategory('');
            
            // Set headers with bold formatting
            $sheet->getCell('A1')->setValueExplicit('Teks Soal', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('B1')->setValueExplicit('Tipe Soal', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('C1')->setValueExplicit('Jawaban', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('D1')->setValueExplicit('Opsi A', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('E1')->setValueExplicit('Opsi B', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('F1')->setValueExplicit('Opsi C', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('G1')->setValueExplicit('Opsi D', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('H1')->setValueExplicit('Opsi E', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->getCell('I1')->setValueExplicit('Tingkat kesulitan', \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            
            // Apply bold formatting to header row
            $sheet->getStyle('A1:I1')->getFont()->setBold(true);
            
            // Add data rows
            $row = 2;
            foreach ($allQuestions as $question) {
                $questionText = $question->question ?? '';
                $questionType = $question->tipe_soal ?? 'pilihan_ganda';
                $answer = $question->answer ?? 'A';
                $optionA = $question->option_a ?? '';
                $optionB = $question->option_b ?? '';
                $optionC = $question->option_c ?? '';
                $optionD = $question->option_d ?? '';
                $optionE = $question->option_e ?? '';
                $difficulty = $question->difficulty ?? '';
                
                $sheet->getCell('A' . $row)->setValueExplicit($questionText, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('B' . $row)->setValueExplicit($questionType, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('C' . $row)->setValueExplicit($answer, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('D' . $row)->setValueExplicit($optionA, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('E' . $row)->setValueExplicit($optionB, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('F' . $row)->setValueExplicit($optionC, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('G' . $row)->setValueExplicit($optionD, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('H' . $row)->setValueExplicit($optionE, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                $sheet->getCell('I' . $row)->setValueExplicit($difficulty, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
                
                $row++;
            }
            
            // Generate filename for category download
            $categoryName = Str::slug($category);
            $filename = 'kategori_' . $categoryName . '.xlsx';
            
            // Save to temp file
            $tempFile = tempnam(sys_get_temp_dir(), 'excel_category_');
            
            $writer = new Xlsx($spreadsheet);
            $writer->setPreCalculateFormulas(false);
            $writer->save($tempFile);
            
            // Verify file
            if (!file_exists($tempFile) || filesize($tempFile) === 0) {
                throw new \Exception('Excel file was not created properly');
            }
            
            // Return download response
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"'
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Error downloading category questions Excel', [
                'category' => $category,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('materials.index')
                           ->with('error', 'Terjadi kesalahan saat mengunduh soal kategori: ' . $e->getMessage());
        }
    }

    /**
     * Show category detail with all questions from materials in that category.
     */
    public function categoryDetail($category)
    {
        try {
            // Get the category model
            $categoryModel = \App\Models\Category::where('name', $category)->firstOrFail();
            
            // Get all sub categories for this category
            $subCategories = $categoryModel->subCategories()
                                         ->withCount('materials')
                                         ->orderBy('created_at', 'desc')
                                         ->get();
            
            // Get all materials in the category
            $materials = Material::where('category', $category)
                              ->with(['questions' => function($query) {
                                  $query->orderBy('created_at', 'desc');
                              }])
                              ->orderBy('created_at', 'desc')
                              ->get();
            
            // Always allow access to category detail - even if empty
            // User should be able to add sub categories and materials

            // Get all questions with pagination
            $questions = Question::whereHas('material', function($query) use ($category) {
                $query->where('category', $category);
            })
            ->with('material')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            // Calculate statistics
            $totalMaterials = $materials->count();
            $totalQuestions = $materials->sum(function($material) {
                return $material->questions->count();
            });
            $totalSubCategories = $subCategories->count();

            return view('materials.category-detail', compact(
                'category', 
                'categoryModel',
                'materials', 
                'questions', 
                'subCategories',
                'totalMaterials', 
                'totalQuestions',
                'totalSubCategories'
            ));

        } catch (\Exception $e) {
            Log::error('Error loading category detail', [
                'category' => $category,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('materials.index')
                           ->with('error', 'Terjadi kesalahan saat memuat detail kategori: ' . $e->getMessage());
        }
    }

    /**
     * Move all materials in a specific category to "Uncategorized".
     */
    public function destroyCategory($category)
    {
        try {
            // Get all materials in the category
            $materials = Material::where('category', $category)->get();
            
            if ($materials->isEmpty()) {
                return redirect()->route('materials.index')
                               ->with('error', 'Tidak ada materi ditemukan untuk kategori: ' . $category);
            }

            $materialsCount = $materials->count();

            // Move all materials to "Uncategorized" instead of deleting them
            foreach ($materials as $material) {
                $material->update(['category' => null]); // or set to 'Uncategorized'
            }

            return redirect()->route('materials.index')
                           ->with('success', "Kategori '{$category}' berhasil dihapus! {$materialsCount} materi telah dipindahkan ke kategori 'Tidak Berkategori'.");

        } catch (\Exception $e) {
            Log::error('Error deleting category', [
                'category' => $category,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('materials.index')
                           ->with('error', 'Terjadi kesalahan saat menghapus kategori: ' . $e->getMessage());
        }
    }

    /**
     * Store a new category.
     */
    public function storeCategory(Request $request)
    {
        try {
            $request->validate([
                'categoryName' => 'required|string|max:100',
                'categoryDescription' => 'nullable|string|max:255',
            ]);

            $categoryName = trim($request->categoryName);
            $categoryDescription = trim($request->categoryDescription);

            // Check if category already exists
            $existingCategory = Category::where('name', $categoryName)->first();
            if ($existingCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori "' . $categoryName . '" sudah ada!'
                ], 409);
            }

            // Create new category
            Category::create([
                'name' => $categoryName,
                'description' => $categoryDescription,
                'is_active' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori "' . $categoryName . '" berhasil dibuat!'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membuat kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing a category.
     */
    public function editCategory($id)
    {
        try {
            $category = Category::findOrFail($id);
            return response()->json([
                'success' => true,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading category for edit', [
                'category_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update a category.
     */
    public function updateCategory(Request $request, $id)
    {
        try {
            $request->validate([
                'categoryName' => 'required|string|max:100',
                'categoryDescription' => 'nullable|string|max:255',
            ]);

            $category = Category::findOrFail($id);
            $categoryName = trim($request->categoryName);
            $categoryDescription = trim($request->categoryDescription);

            // Check if category name already exists (excluding current category)
            $existingCategory = Category::where('name', $categoryName)
                                      ->where('id', '!=', $id)
                                      ->first();
            if ($existingCategory) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori dengan nama "' . $categoryName . '" sudah ada!'
                ], 422);
            }

            // If category name is changed, update all materials with the old category name
            if ($category->name !== $categoryName) {
                Material::where('category', $category->name)
                        ->update(['category' => $categoryName]);
            }

            // Update category
            $category->update([
                'name' => $categoryName,
                'description' => $categoryDescription,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui!',
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                    'description' => $category->description
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating category', [
                'category_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui kategori: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a category by ID and move associated materials to "Uncategorized".
     */
    public function destroyCategoryById($id)
    {
        try {
            $category = Category::findOrFail($id);
            $categoryName = $category->name;
            
            // Get all materials in the category
            $materials = Material::where('category', $categoryName)->get();
            
            $materialsCount = $materials->count();

            // Move all materials to "Uncategorized" instead of deleting them
            foreach ($materials as $material) {
                $material->update(['category' => null]); // or set to 'Uncategorized'
            }

            // Delete the category itself
            $category->delete();

            $message = "Kategori '{$categoryName}' berhasil dihapus!";
            if ($materialsCount > 0) {
                $message .= " {$materialsCount} materi telah dipindahkan ke kategori 'Tidak Berkategori'.";
            }

            return redirect()->route('materials.index')
                           ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Error deleting category by ID', [
                'category_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('materials.index')
                           ->with('error', 'Terjadi kesalahan saat menghapus kategori: ' . $e->getMessage());
        }
    }

    /**
     * Store a new sub category.
     */
    public function storeSubCategory(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category_id' => 'required|exists:categories,id',
            ], [
                'name.required' => 'Nama sub kategori harus diisi.',
                'name.max' => 'Nama sub kategori maksimal 255 karakter.',
                'category_id.required' => 'Kategori harus dipilih.',
                'category_id.exists' => 'Kategori yang dipilih tidak valid.',
            ]);

            $subCategory = \App\Models\SubCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'is_active' => true,
            ]);

            $category = \App\Models\Category::findOrFail($request->category_id);
            
            // Handle AJAX request
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sub kategori berhasil ditambahkan!',
                    'sub_category' => $subCategory,
                    'redirect_url' => route('materials.category.detail', ['category' => $category->name])
                ]);
            }
            
            return redirect()->route('materials.category.detail', ['category' => $category->name])
                           ->with('success', 'Sub kategori berhasil ditambahkan!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $e->validator->errors()->all()),
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error creating sub category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menambahkan sub kategori: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withInput()
                        ->with('error', 'Terjadi kesalahan saat menambahkan sub kategori: ' . $e->getMessage());
        }
    }

    /**
     * Delete a sub category.
     */
    public function destroySubCategory($id)
    {
        try {
            $subCategory = \App\Models\SubCategory::findOrFail($id);
            $categoryName = $subCategory->category->name;
            $subCategoryName = $subCategory->name;
            
            // Get materials count in this sub category
            $materialsCount = $subCategory->materials()->count();
            
            // Set materials in this sub category to have no sub category
            if ($materialsCount > 0) {
                $subCategory->materials()->update(['sub_category_id' => null]);
            }
            
            $subCategory->delete();

            $message = "Sub kategori '{$subCategoryName}' berhasil dihapus!";
            if ($materialsCount > 0) {
                $message .= " {$materialsCount} materi yang ada di sub kategori ini telah dipindahkan ke kategori utama.";
            }

            return redirect()->route('materials.category.detail', ['category' => $categoryName])
                           ->with('success', $message);

        } catch (\Exception $e) {
            Log::error('Error deleting sub category', [
                'sub_category_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->with('error', 'Terjadi kesalahan saat menghapus sub kategori: ' . $e->getMessage());
        }
    }
}
