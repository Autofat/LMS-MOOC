<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MaterialApiController extends Controller
{
    /**
     * Get all materials
     */
    public function index()
    {
        try {
            $materials = Material::withCount('questions')->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $materials,
                'message' => 'Materials retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve materials: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new material via API
     */
    public function store(Request $request)
    {
        try {
            Log::info('API Material upload started', [
                'request_data' => $request->except(['pdf_file']),
                'has_file' => $request->hasFile('pdf_file')
            ]);

            // Basic validation first
                        $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:100',
                'pdf_file' => 'nullable|file|max:10240', // Max 10MB, removed mimes:pdf
            ]);

            if ($validator->fails()) {
                Log::error('API Basic validation failed', ['errors' => $validator->errors()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('pdf_file');
            
            // Custom PDF validation
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $fileMimeType = $file->getMimeType();
            $fileSize = $file->getSize();
            
            Log::info('API File details', [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $fileExtension,
                'mime_type' => $fileMimeType,
                'size' => $fileSize
            ]);

            // Check file extension
            if ($fileExtension !== 'pdf') {
                Log::error('API Invalid file extension', ['extension' => $fileExtension]);
                return response()->json([
                    'success' => false,
                    'message' => 'File harus berformat PDF. Ekstensi file yang diterima: .pdf',
                    'details' => [
                        'detected_extension' => $fileExtension,
                        'file_name' => $file->getClientOriginalName()
                    ]
                ], 422);
            }

            // Check MIME type (flexible for different PDF creators)
            $allowedMimeTypes = [
                'application/pdf',
                'application/x-pdf',
                'application/x-download',
                'application/octet-stream'
            ];

            if (!in_array($fileMimeType, $allowedMimeTypes)) {
                Log::warning('API Uncommon MIME type detected', [
                    'mime_type' => $fileMimeType,
                    'file_name' => $file->getClientOriginalName()
                ]);
            }

            // Verify PDF header (read first 4 bytes)
            $fileContent = file_get_contents($file->getRealPath(), false, null, 0, 4);
            if ($fileContent !== '%PDF') {
                Log::error('API Invalid PDF header', [
                    'header_bytes' => bin2hex($fileContent),
                    'file_name' => $file->getClientOriginalName()
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'File yang diupload bukan file PDF yang valid',
                    'details' => [
                        'mime_type' => $fileMimeType,
                        'file_name' => $file->getClientOriginalName(),
                        'reason' => 'Header file tidak sesuai format PDF'
                    ]
                ], 422);
            }

            Log::info('API PDF validation passed');

            // Generate unique filename
            $fileName = time() . '_' . $file->getClientOriginalName();
            
            // Store file in storage/app/public/materials
            $filePath = $file->storeAs('materials', $fileName, 'public');
            
            // Create material record
            $material = Material::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'category' => $request->category,
                'is_active' => true,
            ]);

            Log::info('API Material created successfully', ['material_id' => $material->id]);

            return response()->json([
                'success' => true,
                'data' => $material,
                'message' => 'Material uploaded successfully',
                'material_id' => $material->id
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific material
     */
    public function show($id)
    {
        try {
            $material = Material::with('questions')->find($id);
            
            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $material,
                'message' => 'Material retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update material
     */
    public function update(Request $request, $id)
    {
        try {
            $material = Material::find($id);
            
            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:100',
                'pdf_file' => 'nullable|file|max:10240', // Max 10MB, removed mimes:pdf
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload if new file is provided
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                
                // Custom PDF validation
                $fileExtension = strtolower($file->getClientOriginalExtension());
                $fileMimeType = $file->getMimeType();
                
                Log::info('API Update file details', [
                    'original_name' => $file->getClientOriginalName(),
                    'extension' => $fileExtension,
                    'mime_type' => $fileMimeType,
                    'size' => $file->getSize()
                ]);

                // Check file extension
                if ($fileExtension !== 'pdf') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File harus berformat PDF. Ekstensi file yang diterima: .pdf',
                        'details' => [
                            'detected_extension' => $fileExtension,
                            'file_name' => $file->getClientOriginalName()
                        ]
                    ], 422);
                }

                // Verify PDF header
                $fileContent = file_get_contents($file->getRealPath(), false, null, 0, 4);
                if ($fileContent !== '%PDF') {
                    return response()->json([
                        'success' => false,
                        'message' => 'File yang diupload bukan file PDF yang valid',
                        'details' => [
                            'mime_type' => $fileMimeType,
                            'file_name' => $file->getClientOriginalName(),
                            'reason' => 'Header file tidak sesuai format PDF'
                        ]
                    ], 422);
                }
                
                // Delete old file
                if (Storage::disk('public')->exists($material->file_path)) {
                    Storage::disk('public')->delete($material->file_path);
                }
                
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('materials', $fileName, 'public');
                
                $material->update([
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                ]);
            }

            $material->update([
                'title' => $request->title,
                'description' => $request->description,
                'category' => $request->category,
            ]);

            return response()->json([
                'success' => true,
                'data' => $material,
                'message' => 'Material updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete material
     */
    public function destroy($id)
    {
        try {
            $material = Material::find($id);
            
            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material not found'
                ], 404);
            }

            // Delete file from storage
            if (Storage::disk('public')->exists($material->file_path)) {
                Storage::disk('public')->delete($material->file_path);
            }
            
            $material->delete();

            return response()->json([
                'success' => true,
                'message' => 'Material deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete material: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get materials with their questions count
     */
    public function materialsWithQuestions()
    {
        try {
            $materials = Material::withCount('questions')
                ->with(['questions' => function($query) {
                    $query->select('id', 'material_id', 'question', 'difficulty', 'created_at');
                }])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $materials,
                'message' => 'Materials with questions retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve materials with questions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook endpoint for n8n automation
     * This will be called when a material is uploaded via n8n
     */
    public function webhook(Request $request)
    {
        try {
            // Log incoming webhook data for debugging
            Log::info('Material webhook received:', $request->all());

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:100',
                'pdf_file' => 'required|file|max:10240', // Removed mimes:pdf
                'automation_id' => 'nullable|string', // For n8n tracking
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $file = $request->file('pdf_file');
            
            // Custom PDF validation for webhook
            $fileExtension = strtolower($file->getClientOriginalExtension());
            $fileMimeType = $file->getMimeType();
            
            Log::info('Webhook file details', [
                'original_name' => $file->getClientOriginalName(),
                'extension' => $fileExtension,
                'mime_type' => $fileMimeType,
                'size' => $file->getSize()
            ]);

            // Check file extension
            if ($fileExtension !== 'pdf') {
                return response()->json([
                    'success' => false,
                    'message' => 'File harus berformat PDF. Ekstensi file yang diterima: .pdf',
                    'details' => [
                        'detected_extension' => $fileExtension,
                        'file_name' => $file->getClientOriginalName()
                    ]
                ], 422);
            }

            // Verify PDF header
            $fileContent = file_get_contents($file->getRealPath(), false, null, 0, 4);
            if ($fileContent !== '%PDF') {
                return response()->json([
                    'success' => false,
                    'message' => 'File yang diupload bukan file PDF yang valid',
                    'details' => [
                        'mime_type' => $fileMimeType,
                        'file_name' => $file->getClientOriginalName(),
                        'reason' => 'Header file tidak sesuai format PDF'
                    ]
                ], 422);
            }

            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('materials', $fileName, 'public');
            
            $material = Material::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'category' => $request->category,
                'is_active' => true,
            ]);

            // Return material_id for n8n automation
            return response()->json([
                'success' => true,
                'data' => $material,
                'material_id' => $material->id,
                'message' => 'Material uploaded successfully via webhook',
                'automation_id' => $request->automation_id ?? null,
                'webhook_response' => [
                    'material_id' => $material->id,
                    'title' => $material->title,
                    'file_name' => $material->file_name,
                    'created_at' => $material->created_at->toISOString(),
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('Material webhook error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
