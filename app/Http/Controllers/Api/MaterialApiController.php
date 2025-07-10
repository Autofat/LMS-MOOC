<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Material;
use App\Models\Question;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:100',
                'pdf_file' => 'required|file|mimes:pdf|max:10240', // Max 10MB
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

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
                'pdf_file' => 'nullable|file|mimes:pdf|max:10240', // Max 10MB
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
            \Log::info('Material webhook received:', $request->all());

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'category' => 'nullable|string|max:100',
                'pdf_file' => 'required|file|mimes:pdf|max:10240',
                'automation_id' => 'nullable|string', // For n8n tracking
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $uploadedFile = $request->file('pdf_file');
            $fileName = time() . '_' . $uploadedFile->getClientOriginalName();
            $filePath = $uploadedFile->storeAs('materials', $fileName, 'public');
            
            $material = Material::create([
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
                'file_name' => $uploadedFile->getClientOriginalName(),
                'file_size' => $uploadedFile->getSize(),
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
            \Log::error('Material webhook error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get questions count for a specific material
     */
    public function getQuestionsCount($id)
    {
        try {
            $material = Material::findOrFail($id);
            $questionsCount = $material->questions()->count();
            
            return response()->json([
                'success' => true,
                'material_id' => $material->id,
                'count' => $questionsCount,
                'message' => 'Questions count retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get questions count: ' . $e->getMessage()
            ], 500);
        }
    }
}
