<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class MaterialController extends Controller
{
    /**
     * Display a listing of materials.
     */
    public function index()
    {
        $materials = Material::withCount('questions')->orderBy('created_at', 'desc')->paginate(10);
        return view('materials.index', compact('materials'));
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
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'pdf_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        $uploadedFile = $request->file('pdf_file');
        
        // Generate unique filename
        $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
        
        // Store file in storage/app/public/materials
        $filePath = $uploadedFile->storeAs('materials', $fileName, 'public');
        
        // Create material record
        $material = Material::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'file_name' => $uploadedFile->getClientOriginalName(),
            'file_size' => $uploadedFile->getSize(),
            'category' => $request->category,
            'is_active' => true,
        ]);

        // Notify n8n
        \Log::info('About to call notifyN8n', ['material_id' => $material->id]);
        $webhookResult = $this->notifyN8n($material);
        \Log::info('notifyN8n result', ['material_id' => $material->id, 'result' => $webhookResult]);

        return redirect()->route('materials.index')->with('success', 'Materi berhasil diupload!');
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
        return view('materials.edit', compact('material'));
    }

    /**
     * Update the specified material in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
        ]);

        $material = Material::findOrFail($id);
        
        // Handle file upload if new file is provided
        if ($request->hasFile('pdf_file')) {
            // Delete old file
            if (Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            
            $uploadedFile = $request->file('pdf_file');
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $filePath = $uploadedFile->storeAs('materials', $fileName, 'public');
            
            $material->update([
                'file_path' => $filePath,
                'file_name' => $uploadedFile->getClientOriginalName(),
                'file_size' => $uploadedFile->getSize(),
            ]);
        }

        $material->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
        ]);

        return redirect()->route('materials.index')->with('success', 'Materi berhasil diupdate!');
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
            return Storage::disk('public')->download($material->file_path, $material->file_name);
        }
        
        return redirect()->back()->with('error', 'File tidak ditemukan!');
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
        \Log::info('notifyN8n method called', [
            'material_id' => $material->id,
            'material_title' => $material->title
        ]);
        
        try {
            // URL n8n webhook - bisa dikonfigurasi di .env
            $n8nWebhookUrl = env('N8N_WEBHOOK_URL', 'http://localhost:5678/webhook-test/uploadFiles');
            
            \Log::info('Using webhook URL', ['webhook_url' => $n8nWebhookUrl]);
            
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
                'api_file_content_url' => url('/api/materials/' . $material->id . '/file-content'),
                'api_file_stream_url' => url('/api/materials/' . $material->id . '/stream'),
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
                        \Log::warning('File too large for base64 encoding', [
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
                    \Log::error('Error processing file for n8n', [
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
            \Log::info('Attempting to send webhook to n8n', [
                'material_id' => $material->id,
                'webhook_url' => $n8nWebhookUrl,
                'file_size' => $material->file_size,
                'file_exists' => file_exists($filePath),
                'has_base64' => isset($data['file_content_base64'])
            ]);

            // Kirim ke n8n menggunakan HTTP client dengan error handling untuk JSON
            try {
                $response = \Http::timeout(30)->post($n8nWebhookUrl, $data);
                
                if ($response->successful()) {
                    \Log::info('n8n notification sent successfully', [
                        'material_id' => $material->id,
                        'response_status' => $response->status(),
                        'response_body' => $response->body()
                    ]);
                    return true;
                } else {
                    \Log::error('n8n notification failed', [
                        'material_id' => $material->id,
                        'status' => $response->status(),
                        'response' => $response->body(),
                        'webhook_url' => $n8nWebhookUrl
                    ]);
                    return false;
                }
            } catch (\Exception $httpError) {
                \Log::error('HTTP error sending to n8n', [
                    'material_id' => $material->id,
                    'error' => $httpError->getMessage(),
                    'webhook_url' => $n8nWebhookUrl
                ]);
                return false;
            }
        } catch (\Exception $e) {
            \Log::error('n8n notification error', [
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
            \Log::error('Error resending to n8n', [
                'material_id' => $id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
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
            
            \Log::info('Bulk send to n8n completed', [
                'total_materials' => $materials->count(),
                'success_count' => $successCount,
                'error_count' => $errorCount
            ]);
            
            return redirect()->back()->with('success', 
                "Bulk send completed! Success: {$successCount}, Errors: {$errorCount}"
            );
            
        } catch (\Exception $e) {
            \Log::error('Error bulk sending to n8n', [
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
                \Log::error('File not found for material', [
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
            \Log::error('Error retrieving file for n8n', [
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
                \Log::error('File not found for streaming', [
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
            \Log::error('Error streaming file for n8n', [
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
}