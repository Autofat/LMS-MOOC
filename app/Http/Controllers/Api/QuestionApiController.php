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
            \Log::info('Raw webhook content:', ['content' => $request->getContent()]);

            // Handle both JSON string and parsed data
            $rawInput = $request->getContent();
            $inputData = null;
            
            // Try to parse as JSON if it's a string
            if (is_string($rawInput) && !empty($rawInput)) {
                try {
                    $inputData = json_decode($rawInput, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        \Log::error('Webhook JSON decode error: ' . json_last_error_msg());
                        $inputData = $request->all();
                    }
                } catch (\Exception $e) {
                    \Log::error('Webhook JSON parsing failed: ' . $e->getMessage());
                    $inputData = $request->all();
                }
            } else {
                $inputData = $request->all();
            }
            
            \Log::info('Processed webhook data:', ['data' => $inputData]);

            // Cek apakah data berupa single question atau array questions
            if (isset($inputData['question']) && !isset($inputData['questions'])) {
                // Single question format
                $cleanData = [
                    'material_id' => $inputData['material_id'] ?? null,
                    'question' => $inputData['question'] ?? null,
                    'options' => $inputData['options'] ?? [],
                    'answer' => $inputData['answer'] ?? null,
                    'explanation' => $inputData['explanation'] ?? null,
                    'difficulty' => $inputData['difficulty'] ?? 'menengah',
                ];
                
                $validator = Validator::make($cleanData, [
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
                    'material_id' => $cleanData['material_id'],
                    'question' => $cleanData['question'],
                    'options' => [
                        'A' => $cleanData['options']['A'],
                        'B' => $cleanData['options']['B'],
                        'C' => $cleanData['options']['C'],
                        'D' => $cleanData['options']['D'],
                    ],
                    'answer' => $cleanData['answer'],
                    'explanation' => $cleanData['explanation'],
                    'difficulty' => $cleanData['difficulty'],
                ]);

                $material = Material::find($inputData['material_id']);

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
                if (!isset($inputData['questions']) || !is_array($inputData['questions'])) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Questions array is required for bulk creation',
                        'received_data' => $inputData
                    ], 400);
                }
                
                $questions = $inputData['questions'];
                $materialId = $inputData['material_id'] ?? null;
                
                if (!$materialId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Material ID is required'
                    ], 400);
                }
                
                $cleanData = [
                    'material_id' => $materialId,
                    'questions' => $questions
                ];
                
                $validator = Validator::make($cleanData, [
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
                $material = Material::find($materialId);

                foreach ($questions as $questionData) {
                    $question = Question::create([
                        'material_id' => $materialId,
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
            
            // Handle both JSON string and array input
            $rawInput = $request->getContent();
            $inputData = null;
            
            // Try to parse as JSON if it's a string
            if (is_string($rawInput) && !empty($rawInput)) {
                try {
                    $inputData = json_decode($rawInput, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        \Log::error('JSON decode error: ' . json_last_error_msg());
                        \Log::error('Raw input preview: ' . substr($rawInput, 0, 1000));
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid JSON format: ' . json_last_error_msg(),
                            'json_error_code' => json_last_error(),
                            'raw_input_preview' => substr($rawInput, 0, 200) . (strlen($rawInput) > 200 ? '...' : ''),
                            'hint' => 'Please check your JSON syntax. Common issues: missing quotes, trailing commas, unclosed brackets.'
                        ], 400);
                    }
                } catch (\Exception $e) {
                    \Log::error('JSON parsing exception: ' . $e->getMessage());
                    \Log::error('Raw input: ' . substr($rawInput, 0, 1000));
                    return response()->json([
                        'success' => false,
                        'message' => 'JSON parsing failed: ' . $e->getMessage(),
                        'raw_input_preview' => substr($rawInput, 0, 200) . (strlen($rawInput) > 200 ? '...' : ''),
                        'hint' => 'Please ensure your JSON is properly formatted and escaped.'
                    ], 400);
                }
            } else {
                $inputData = $request->all();
            }
            
            \Log::info('Processed input data:', ['data' => $inputData, 'type' => gettype($inputData)]);
            
            // Check if we have questions array
            if (!isset($inputData['questions'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Questions array is required',
                    'received_data' => $inputData
                ], 400);
            }
            
            $questions = $inputData['questions'];
            
            // If questions is still a string, try to decode it
            if (is_string($questions)) {
                try {
                    $questions = json_decode($questions, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('Invalid JSON in questions field: ' . json_last_error_msg());
                    }
                } catch (\Exception $e) {
                    \Log::error('Failed to decode questions JSON: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Questions field must be valid JSON array',
                        'error' => $e->getMessage()
                    ], 400);
                }
            }
            
            // Validate that it's an array
            if (!is_array($questions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Questions must be an array',
                    'received_type' => gettype($questions),
                    'received_data' => is_string($questions) ? substr($questions, 0, 100) . '...' : $questions
                ], 400);
            }
            
            // Check if questions array is empty
            if (empty($questions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Questions array cannot be empty. Please provide at least one question.',
                    'received_count' => count($questions)
                ], 400);
            }
            
            $createdQuestions = [];
            $errors = [];
            $skippedQuestions = [];
            
            foreach ($questions as $index => $questionData) {
                try {
                    // Skip if not array
                    if (!is_array($questionData)) {
                        \Log::warning("Skipping non-array question at index {$index}", ['data' => $questionData]);
                        $skippedQuestions[$index] = 'Question data is not an array';
                        continue;
                    }
                    
                    // Skip if missing required fields
                    $requiredFields = ['material_id', 'question', 'options', 'answer'];
                    $missingFields = [];
                    
                    foreach ($requiredFields as $field) {
                        if (!isset($questionData[$field]) || empty($questionData[$field])) {
                            $missingFields[] = $field;
                        }
                    }
                    
                    if (!empty($missingFields)) {
                        $errors[$index] = "Missing required fields: " . implode(', ', $missingFields);
                        \Log::warning("Missing required fields for question {$index}", ['missing' => $missingFields, 'data' => $questionData]);
                        continue;
                    }
                    
                    // Check if options is an array with A, B, C, D
                    if (!is_array($questionData['options'])) {
                        $errors[$index] = 'Options must be an array';
                        \Log::warning("Options not array for question {$index}", ['options' => $questionData['options']]);
                        continue;
                    }
                    
                    $missingOptions = [];
                    foreach (['A', 'B', 'C', 'D'] as $option) {
                        if (!isset($questionData['options'][$option]) || empty(trim($questionData['options'][$option]))) {
                            $missingOptions[] = $option;
                        }
                    }
                    
                    if (!empty($missingOptions)) {
                        $errors[$index] = "Missing options: " . implode(', ', $missingOptions);
                        \Log::warning("Missing options for question {$index}", ['missing_options' => $missingOptions]);
                        continue;
                    }
                    
                    // Validate question text
                    if (!is_string($questionData['question']) || empty(trim($questionData['question']))) {
                        $errors[$index] = 'Question text is required and must be a non-empty string';
                        \Log::warning("Invalid question text for question {$index}", ['question' => $questionData['question']]);
                        continue;
                    }
                    
                    // Validate answer
                    if (!in_array($questionData['answer'], ['A', 'B', 'C', 'D'])) {
                        $errors[$index] = 'Answer must be A, B, C, or D';
                        \Log::warning("Invalid answer for question {$index}", ['answer' => $questionData['answer']]);
                        continue;
                    }
                    
                    // Clean and prepare data for validation and creation
                    $cleanQuestionData = [
                        'material_id' => (int) $questionData['material_id'],
                        'question' => trim($questionData['question']),
                        'options' => [
                            'A' => trim($questionData['options']['A']),
                            'B' => trim($questionData['options']['B']),
                            'C' => trim($questionData['options']['C']),
                            'D' => trim($questionData['options']['D']),
                        ],
                        'answer' => $questionData['answer'],
                        'explanation' => isset($questionData['explanation']) && !empty(trim($questionData['explanation'])) ? trim($questionData['explanation']) : null,
                        'difficulty' => isset($questionData['difficulty']) && !empty(trim($questionData['difficulty'])) ? strtolower(trim($questionData['difficulty'])) : 'menengah',
                    ];
                    
                    // Normalize difficulty
                    if (!in_array($cleanQuestionData['difficulty'], ['mudah', 'menengah', 'sulit'])) {
                        $cleanQuestionData['difficulty'] = 'menengah';
                    }
                    
                    \Log::info("Validating question {$index}", ['data' => $cleanQuestionData]);
                    
                    // Validate individual question with clean data
                    $validator = Validator::make($cleanQuestionData, [
                        'material_id' => 'required|integer|exists:materials,id',
                        'question' => 'required|string|min:1',
                        'options' => 'required|array',
                        'options.A' => 'required|string|min:1',
                        'options.B' => 'required|string|min:1',
                        'options.C' => 'required|string|min:1',
                        'options.D' => 'required|string|min:1',
                        'answer' => 'required|in:A,B,C,D',
                        'explanation' => 'nullable|string',
                        'difficulty' => 'required|in:mudah,menengah,sulit',
                    ]);
                    
                    if ($validator->fails()) {
                        $errors[$index] = $validator->errors()->toArray();
                        \Log::error("Validation failed for question {$index}", $validator->errors()->toArray());
                        continue;
                    }
                    
                    // Create question using clean data
                    $question = Question::create($cleanQuestionData);
                    
                    $createdQuestions[] = $question;
                    \Log::info("Question created successfully", [
                        'id' => $question->id,
                        'material_id' => $question->material_id,
                        'index' => $index
                    ]);

                } catch (\Exception $e) {
                    $errors[$index] = $e->getMessage();
                    \Log::error("Error creating question {$index}: " . $e->getMessage());
                    \Log::error("Stack trace: " . $e->getTraceAsString());
                }
            }
            
            // Get material info if we have successful questions
            $material = null;
            if (!empty($createdQuestions)) {
                $material = Material::find($createdQuestions[0]->material_id);
            }
            
            $totalProcessed = count($questions);
            $totalCreated = count($createdQuestions);
            $totalErrors = count($errors);
            $totalSkipped = count($skippedQuestions);
            
            return response()->json([
                'success' => $totalCreated > 0,
                'data' => [
                    'material' => $material,
                    'questions' => $createdQuestions,
                    'total_processed' => $totalProcessed,
                    'total_created' => $totalCreated,
                    'total_errors' => $totalErrors,
                    'total_skipped' => $totalSkipped,
                    'errors' => $errors,
                    'skipped' => $skippedQuestions
                ],
                'message' => "{$totalCreated} out of {$totalProcessed} questions created successfully"
            ], $totalCreated > 0 ? 201 : 400);
            
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