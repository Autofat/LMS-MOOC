<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Question;
use App\Models\Material;
use Illuminate\Support\Facades\Validator;

class QuestionApiController extends Controller
{
    /**
     * Get all questions
     */
    public function index()
    {
        try {
            $questions = Question::with('material')->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $questions,
                'message' => 'Questions retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve questions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new question via API - Updated untuk format JSON baru
     */
    public function store(Request $request)
    {
        try {
            // Log incoming request data
            \Log::info('Question API Store - Received data:', $request->all());
            \Log::info('Material ID received:', ['material_id' => $request->material_id, 'type' => gettype($request->material_id)]);
            
            // Check if material exists first
            $material = Material::find($request->material_id);
            if (!$material) {
                \Log::error('Material not found:', ['material_id' => $request->material_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Material not found with ID: ' . $request->material_id,
                    'available_materials' => Material::select('id', 'title')->get()
                ], 404);
            }
            
            $validator = Validator::make($request->all(), [
                'material_id' => 'required|integer|exists:materials,id',
                'question' => 'required|string',
                'options' => 'required|array',
                'options.A' => 'required|string',
                'options.B' => 'required|string',
                'options.C' => 'required|string',
                'options.D' => 'required|string',
                'answer' => 'required|in:A,B,C,D',
                'explanation' => 'nullable|string',
                'difficulty' => 'nullable|in:mudah,menengah,sulit',
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Prepare data for creation
            $questionData = [
                'material_id' => (int) $request->material_id,
                'question' => trim($request->question),
                'options' => [
                    'A' => trim($request->options['A']),
                    'B' => trim($request->options['B']),
                    'C' => trim($request->options['C']),
                    'D' => trim($request->options['D']),
                ],
                'answer' => $request->answer,
                'explanation' => $request->explanation ? trim($request->explanation) : null,
                'difficulty' => $request->difficulty ?? 'menengah',
            ];
            
            \Log::info('Creating question with data:', $questionData);

            $question = Question::create($questionData);
            
            \Log::info('Question created successfully:', [
                'id' => $question->id,
                'material_id' => $question->material_id,
                'question' => substr($question->question, 0, 50) . '...'
            ]);

            $question->load('material');

            return response()->json([
                'success' => true,
                'data' => $question,
                'message' => 'Question created successfully'
            ], 201);

        } catch (\Exception $e) {
            \Log::error('Failed to create question: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update question - Updated untuk format JSON baru
     */
    public function update(Request $request, $id)
    {
        try {
            $question = Question::find($id);
            
            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Question not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'material_id' => 'required|exists:materials,id',
                'question' => 'required|string',
                'options' => 'required|array',
                'options.A' => 'required|string',
                'options.B' => 'required|string',
                'options.C' => 'required|string',
                'options.D' => 'required|string',
                'answer' => 'required|in:A,B,C,D',
                'explanation' => 'nullable|string',
                'difficulty' => 'nullable|in:mudah,menengah,sulit',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $question->update([
                'material_id' => $request->material_id,
                'question' => $request->question,
                'options' => [
                    'A' => $request->options['A'],
                    'B' => $request->options['B'],
                    'C' => $request->options['C'],
                    'D' => $request->options['D'],
                ],
                'answer' => $request->answer,
                'explanation' => $request->explanation,
                'difficulty' => $request->difficulty ?? 'menengah',
            ]);

            $question->load('material');

            return response()->json([
                'success' => true,
                'data' => $question,
                'message' => 'Question updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk create questions - Updated untuk format JSON baru
     */
    public function bulkStore(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'material_id' => 'required|exists:materials,id',
                'questions' => 'required|array|min:1',
                'questions.*.question' => 'required|string',
                'questions.*.options' => 'required|array',
                'questions.*.options.A' => 'required|string',
                'questions.*.options.B' => 'required|string',
                'questions.*.options.C' => 'required|string',
                'questions.*.options.D' => 'required|string',
                'questions.*.answer' => 'required|in:A,B,C,D',
                'questions.*.explanation' => 'nullable|string',
                'questions.*.difficulty' => 'nullable|in:mudah,menengah,sulit',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $createdQuestions = [];
            $material = Material::find($request->material_id);

            foreach ($request->questions as $questionData) {
                $question = Question::create([
                    'material_id' => $request->material_id,
                    'question' => $questionData['question'],
                    'options' => [
                        'A' => $questionData['options']['A'],
                        'B' => $questionData['options']['B'],
                        'C' => $questionData['options']['C'],
                        'D' => $questionData['options']['D'],
                    ],
                    'answer' => $questionData['answer'],
                    'explanation' => $questionData['explanation'] ?? null,
                    'difficulty' => $questionData['difficulty'] ?? 'menengah',
                ]);

                $createdQuestions[] = $question;
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'material' => $material,
                    'questions' => $createdQuestions,
                    'total_created' => count($createdQuestions)
                ],
                'message' => 'Questions created successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create questions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Webhook endpoint untuk n8n - Updated untuk format JSON baru
     */
    public function webhook(Request $request)
    {
        try {
            // Log incoming webhook data for debugging
            \Log::info('Question webhook received:', $request->all());

            // Cek apakah data berupa single question atau array questions
            if (isset($request->question) && !isset($request->questions)) {
                // Single question format
                $validator = Validator::make($request->all(), [
                    'material_id' => 'required|exists:materials,id',
                    'question' => 'required|string',
                    'options' => 'required|array',
                    'options.A' => 'required|string',
                    'options.B' => 'required|string',
                    'options.C' => 'required|string',
                    'options.D' => 'required|string',
                    'answer' => 'required|in:A,B,C,D',
                    'explanation' => 'nullable|string',
                    'difficulty' => 'nullable|in:mudah,menengah,sulit',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $question = Question::create([
                    'material_id' => $request->material_id,
                    'question' => $request->question,
                    'options' => [
                        'A' => $request->options['A'],
                        'B' => $request->options['B'],
                        'C' => $request->options['C'],
                        'D' => $request->options['D'],
                    ],
                    'answer' => $request->answer,
                    'explanation' => $request->explanation,
                    'difficulty' => $request->difficulty ?? 'menengah',
                ]);

                $material = Material::find($request->material_id);

                return response()->json([
                    'success' => true,
                    'data' => [
                        'material' => $material,
                        'question' => $question,
                        'total_created' => 1
                    ],
                    'message' => 'Question created successfully via webhook',
                    'webhook_response' => [
                        'material_id' => $material->id,
                        'material_title' => $material->title,
                        'questions_created' => 1,
                        'created_at' => now()->toISOString(),
                    ]
                ], 201);

            } else {
                // Multiple questions format
                $validator = Validator::make($request->all(), [
                    'material_id' => 'required|exists:materials,id',
                    'questions' => 'required|array|min:1',
                    'questions.*.question' => 'required|string',
                    'questions.*.options' => 'required|array',
                    'questions.*.options.A' => 'required|string',
                    'questions.*.options.B' => 'required|string',
                    'questions.*.options.C' => 'required|string',
                    'questions.*.options.D' => 'required|string',
                    'questions.*.answer' => 'required|in:A,B,C,D',
                    'questions.*.explanation' => 'nullable|string',
                    'questions.*.difficulty' => 'nullable|in:mudah,menengah,sulit',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }

                $createdQuestions = [];
                $material = Material::find($request->material_id);

                foreach ($request->questions as $questionData) {
                    $question = Question::create([
                        'material_id' => $request->material_id,
                        'question' => $questionData['question'],
                        'options' => [
                            'A' => $questionData['options']['A'],
                            'B' => $questionData['options']['B'],
                            'C' => $questionData['options']['C'],
                            'D' => $questionData['options']['D'],
                        ],
                        'answer' => $questionData['answer'],
                        'explanation' => $questionData['explanation'] ?? null,
                        'difficulty' => $questionData['difficulty'] ?? 'menengah',
                    ]);

                    $createdQuestions[] = $question;
                }

                return response()->json([
                    'success' => true,
                    'data' => [
                        'material' => $material,
                        'questions' => $createdQuestions,
                        'total_created' => count($createdQuestions)
                    ],
                    'message' => 'Questions created successfully via webhook',
                    'webhook_response' => [
                        'material_id' => $material->id,
                        'material_title' => $material->title,
                        'questions_created' => count($createdQuestions),
                        'created_at' => now()->toISOString(),
                    ]
                ], 201);
            }

        } catch (\Exception $e) {
            \Log::error('Question webhook error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific question
     */
    public function show($id)
    {
        try {
            $question = Question::with('material')->find($id);
            
            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Question not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $question,
                'message' => 'Question retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete question
     */
    public function destroy($id)
    {
        try {
            $question = Question::find($id);
            
            if (!$question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Question not found'
                ], 404);
            }

            $question->delete();

            return response()->json([
                'success' => true,
                'message' => 'Question deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete question: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get questions by material ID
     */
    public function getByMaterial($materialId)
    {
        try {
            $material = Material::find($materialId);
            
            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material not found'
                ], 404);
            }

            $questions = Question::where('material_id', $materialId)
                ->with('material')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'material' => $material,
                    'questions' => $questions,
                    'total_questions' => $questions->count()
                ],
                'message' => 'Questions retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve questions: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store multiple questions from array format
     */
    public function storeFromArray(Request $request)
    {
        try {
            // Log raw input
            \Log::info('Raw JSON input:', ['raw' => $request->getContent()]);
            \Log::info('Parsed request data:', $request->all());
            
            // Check if we have questions array
            if (!$request->has('questions')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Questions array is required',
                    'received_data' => $request->all()
                ], 400);
            }
            
            $questions = $request->input('questions');
            
            // Validate that it's an array
            if (!is_array($questions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Questions must be an array',
                    'received_type' => gettype($questions)
                ], 400);
            }
            
            $createdQuestions = [];
            $errors = [];
            
            foreach ($questions as $index => $questionData) {
                try {
                    // Skip if not array or missing required fields
                    if (!is_array($questionData) || !isset($questionData['material_id'])) {
                        \Log::warning("Skipping invalid question at index {$index}", ['data' => $questionData]);
                        continue;
                    }
                    
                    // Validate individual question
                    $validator = Validator::make($questionData, [
                        'material_id' => 'required|integer|exists:materials,id',
                        'question' => 'required|string',
                        'options' => 'required|array',
                        'options.A' => 'required|string',
                        'options.B' => 'required|string',
                        'options.C' => 'required|string',
                        'options.D' => 'required|string',
                        'answer' => 'required|in:A,B,C,D',
                        'explanation' => 'nullable|string',
                        'difficulty' => 'nullable|in:mudah,menengah,sulit',
                    ]);
                    
                    if ($validator->fails()) {
                        $errors[$index] = $validator->errors();
                        \Log::error("Validation failed for question {$index}", $validator->errors()->toArray());
                        continue;
                    }
                    
                    // Create question
                    $question = Question::create([
                        'material_id' => (int) $questionData['material_id'],
                        'question' => trim($questionData['question']),
                        'options' => [
                            'A' => trim($questionData['options']['A']),
                            'B' => trim($questionData['options']['B']),
                            'C' => trim($questionData['options']['C']),
                            'D' => trim($questionData['options']['D']),
                        ],
                        'answer' => $questionData['answer'],
                        'explanation' => isset($questionData['explanation']) ? trim($questionData['explanation']) : null,
                        'difficulty' => $questionData['difficulty'] ?? 'menengah',
                    ]);
                    
                    $createdQuestions[] = $question;
                    \Log::info("Question created successfully", [
                        'id' => $question->id,
                        'material_id' => $question->material_id,
                        'index' => $index
                    ]);

                } catch (\Exception $e) {
                    $errors[$index] = $e->getMessage();
                    \Log::error("Error creating question {$index}: " . $e->getMessage());
                }
            }
            
            // Get material info if we have successful questions
            $material = null;
            if (!empty($createdQuestions)) {
                $material = Material::find($createdQuestions[0]->material_id);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                    'material' => $material,
                    'questions' => $createdQuestions,
                    'total_created' => count($createdQuestions),
                    'total_errors' => count($errors),
                    'errors' => $errors
                ],
                'message' => count($createdQuestions) . ' questions created successfully'
            ], 201);
            
        } catch (\Exception $e) {
            \Log::error('Error in storeFromArray: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process questions: ' . $e->getMessage()
            ], 500);
        }
    }
}