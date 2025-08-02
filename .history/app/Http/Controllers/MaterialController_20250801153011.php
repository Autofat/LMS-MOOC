<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        // Enhanced validation with detailed error messages
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:100',
                'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
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
        
        // Comprehensive debugging
        $debugInfo = [
            'has_file' => $request->hasFile('pdf_file'),
            'php_upload_max' => ini_get('upload_max_filesize'),
            'php_post_max' => ini_get('post_max_size'),
            'php_max_execution' => ini_get('max_execution_time'),
            'storage_path_exists' => Storage::disk('public')->exists('materials'),
            'storage_writable' => is_writable(storage_path('app/public')),
        ];
        
        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $debugInfo['file_info'] = [
                'name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'size_mb' => round($file->getSize() / 1024 / 1024, 2),
                'mime' => $file->getMimeType(),
                'extension' => $file->getClientOriginalExtension(),
                'is_valid' => $file->isValid(),
                'error' => $file->getError(),
                'error_message' => $file->getErrorMessage(),
            ];
        }
        
        Log::info('Material Update Debug Info:', $debugInfo);
        
        $fileUpdated = false;
        
        // Handle file upload if new file is provided
        if ($request->hasFile('pdf_file') && $request->file('pdf_file')->isValid()) {
            try {
                // Delete old file
                if (Storage::disk('public')->exists($material->file_path)) {
                    Storage::disk('public')->delete($material->file_path);
                    Log::info('Old file deleted: ' . $material->file_path);
                }
                
                $uploadedFile = $request->file('pdf_file');
                $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
                
                // Ensure materials directory exists
                if (!Storage::disk('public')->exists('materials')) {
                    Storage::disk('public')->makeDirectory('materials');
                    Log::info('Created materials directory');
                }
                
                $filePath = $uploadedFile->storeAs('materials', $fileName, 'public');
                
                if ($filePath) {
                    Log::info('New file uploaded successfully: ' . $filePath);
                    
                    $material->update([
                        'file_path' => $filePath,
                        'file_name' => $uploadedFile->getClientOriginalName(),
                        'file_size' => $uploadedFile->getSize(),
                    ]);
                    
                    $fileUpdated = true;
                } else {
                    Log::error('File upload failed - storeAs returned false');
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

        $material->update([
            'title' => $request->title,
            'description' => $request->description,
            'category' => $request->category,
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
     * Generate questions using n8n workflow
     */
    public function generateQuestions(Request $request, $id)
    {
        // Increase PHP execution time for this request
        set_time_limit(300); // 5 minutes
        ini_set('max_execution_time', 300);
        
        try {
            $material = Material::findOrFail($id);
            
            // Validate request
            $request->validate([
                'question_count' => 'required|integer|min:1|max:50',
                'difficulty' => 'required|in:mudah,menengah,sulit,campuran',
                'auto_generate' => 'boolean'
            ]);
            
            // URL n8n webhook untuk generate questions
            $n8nGenerateUrl = env('N8N_GENERATE_QUESTIONS_URL', 'http://localhost:5678/webhook/generate-questions');
            
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
                'file_url' => asset('storage/' . $material->file_path),
                'download_url' => route('materials.download', $material->id),
                'api_file_content_url' => 'http://172.17.0.1:8000/api/materials/' . $material->id . '/file-content',
                'api_file_stream_url' => 'http://172.17.0.1:8000/api/materials/' . $material->id . '/stream',
                'questions_api_url' => 'http://172.17.0.1:8000/api/questions/array',
                'questions_webhook_url' => 'http://172.17.0.1:8000/api/questions/auto-save',
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
                'callback_url' => 'http://172.17.0.1:8000/materials/' . $material->id
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
                        \Log::warning('Could not include file content in generation request', [
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
            $response = \Http::timeout(30) // Reduce to 30 seconds for faster response
                ->retry(1, 100) // Only retry once
                ->withOptions([
                    'verify' => false, // Disable SSL verification for localhost
                    'allow_redirects' => true,
                    'http_errors' => false // Don't throw exceptions for HTTP error status codes
                ])
                ->post($n8nGenerateUrl, $data);
            
            if ($response->successful()) {
                \Log::info('n8n question generation triggered successfully', [
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
                \Log::error('n8n question generation failed', [
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
            \Log::warning('Connection timeout when triggering question generation - continuing with polling', [
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
                    'difficulty' => $request->difficulty ?? 'menengah',
                    'estimated_completion' => '3-10 minutes',
                    'timeout_occurred' => true
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error triggering question generation', [
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
            
            // Validate request
            $request->validate([
                'question_count' => 'required|integer|min:1|max:50',
                'difficulty' => 'required|in:mudah,menengah,sulit,campuran',
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
                'api_file_content_url' => 'http://172.17.0.1:8000/api/materials/' . $material->id . '/file-content',
                'api_file_stream_url' => 'http://172.17.0.1:8000/api/materials/' . $material->id . '/stream',
                'questions_api_url' => 'http://172.17.0.1:8000/api/questions/array',
                'questions_webhook_url' => 'http://172.17.0.1:8000/api/questions/auto-save',
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
                'callback_url' => 'http://172.17.0.1:8000/materials/' . $material->id
            ];
            
            $n8nGenerateUrl = env('N8N_GENERATE_QUESTIONS_URL', 'http://localhost:5678/webhook/generate-questions');
            
            \Log::info('Triggering n8n async generation', [
                'material_id' => $material->id,
                'webhook_url' => $n8nGenerateUrl
            ]);
            
            // Fire-and-forget with very short timeout
            try {
                \Http::timeout(5) // Only 5 seconds
                    ->withOptions(['verify' => false])
                    ->post($n8nGenerateUrl, $quickData);
            } catch (\Exception $e) {
                // Ignore timeout/connection errors for fire-and-forget
                \Log::info('Fire-and-forget request completed (timeout expected)', [
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
            \Log::error('Error in async question generation', [
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