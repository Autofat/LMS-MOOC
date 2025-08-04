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
            
            // Only clear completion cache if this is a new generation (not individual question storage)
            // Individual questions shouldn't affect completion status
            \Log::info('Storing individual question for material', [
                'material_id' => $request->material_id
            ]);
            
            $validator = Validator::make($request->all(), [
                'material_id' => 'required|integer|exists:materials,id',
                'question' => 'required|string',
                'options' => 'required|array',
                'options.A' => 'required|string',
                'options.B' => 'required|string',
                'options.C' => 'required|string',
                'options.D' => 'required|string',
                'options.E' => 'nullable|string',
                'answer' => 'required|in:A,B,C,D,E',
                'explanation' => 'nullable|string',
                'difficulty' => 'nullable|in:mudah,menengah,sulit,easy,medium,hard',
            ]);

            if ($validator->fails()) {
                \Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Prepare data for creation using individual columns
            $questionData = [
                'material_id' => (int) $request->material_id,
                'question' => trim($request->question),
                'option_A' => trim($request->options['A']),
                'option_B' => trim($request->options['B']),
                'option_C' => trim($request->options['C']),
                'option_D' => trim($request->options['D']),
                'option_E' => isset($request->options['E']) ? trim($request->options['E']) : null,
                'answer' => $request->answer,
                'explanation' => $request->explanation ? trim($request->explanation) : null,
                'difficulty' => $request->difficulty ?? 'medium',
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
                'difficulty' => 'nullable|in:mudah,menengah,sulit,easy,medium,hard',
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
                'difficulty' => $request->difficulty ?? 'medium',
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
    /**
     * Main n8n endpoint for the new JSON format
     * Handles: {"material_id": 38, "questions": [...]}
     */
    public function webhook(Request $request)
    {
        try {
            // Log incoming webhook data
            \Log::info('N8N webhook received:', $request->all());
            
            // Get the JSON data
            $data = $request->all();
            
            // Check if we have the expected format
            if (!isset($data['material_id']) || !isset($data['questions'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid format. Expected: {"material_id": X, "questions": [...]}'
                ], 400);
            }
            
            $materialId = $data['material_id'];
            $questions = $data['questions'];
            
            // Validate material exists
            $material = Material::find($materialId);
            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material not found with ID: ' . $materialId
                ], 404);
            }
            
            // Don't clear completion cache here - let n8n completion endpoint handle the final status
            \Log::info('Processing new questions for material', [
                'material_id' => $materialId,
                'questions_count' => count($questions)
            ]);
            
            // Validate questions array
            if (!is_array($questions) || empty($questions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Questions must be a non-empty array'
                ], 400);
            }
            
            $createdQuestions = [];
            $errors = [];
            
            foreach ($questions as $index => $questionData) {
                try {
                    // Validate required fields
                    if (!isset($questionData['question']) || !isset($questionData['options']) || !isset($questionData['answer'])) {
                        $errors[$index] = 'Missing required fields: question, options, or answer';
                        continue;
                    }
                    
                    // Validate options
                    $options = $questionData['options'];
                    if (!isset($options['A']) || !isset($options['B']) || !isset($options['C']) || !isset($options['D'])) {
                        $errors[$index] = 'Options must include A, B, C, and D';
                        continue;
                    }
                    
                    // Normalize difficulty - Auto-translate Indonesian to English
                    $difficulty = 'medium'; // default (changed to English)
                    if (isset($questionData['difficulty'])) {
                        // Primary mapping: Indonesian to English (for n8n generated questions)
                        $indonesianToEnglishMap = [
                            'mudah' => 'easy',
                            'menengah' => 'medium',
                            'sulit' => 'hard'
                        ];
                        
                        // Secondary mapping: English to English (for consistency)
                        $englishMap = [
                            'easy' => 'easy',
                            'medium' => 'medium',
                            'hard' => 'hard'
                        ];
                        
                        $inputDifficulty = strtolower(trim($questionData['difficulty']));
                        
                        // First try Indonesian to English translation
                        if (isset($indonesianToEnglishMap[$inputDifficulty])) {
                            $difficulty = $indonesianToEnglishMap[$inputDifficulty];
                            \Log::info('Translated difficulty from Indonesian to English', [
                                'original' => $questionData['difficulty'],
                                'translated' => $difficulty
                            ]);
                        }
                        // Then try direct English mapping
                        elseif (isset($englishMap[$inputDifficulty])) {
                            $difficulty = $englishMap[$inputDifficulty];
                        }
                        // Fallback to original value if already in English format
                        elseif (in_array($inputDifficulty, ['easy', 'medium', 'hard'])) {
                            $difficulty = $inputDifficulty;
                        }
                        // Default fallback
                        else {
                            $difficulty = 'medium';
                            \Log::warning('Unknown difficulty level, defaulting to medium', [
                                'original' => $questionData['difficulty'],
                                'defaulted_to' => $difficulty
                            ]);
                        }
                    }
                    
                    // Create the question
                    $question = Question::create([
                        'material_id' => $materialId,
                        'question' => trim($questionData['question']),
                        'option_A' => trim($options['A']),
                        'option_B' => trim($options['B']),
                        'option_C' => trim($options['C']),
                        'option_D' => trim($options['D']),
                        'option_E' => isset($options['E']) && !empty($options['E']) ? trim($options['E']) : null,
                        'answer' => $questionData['answer'],
                        'explanation' => isset($questionData['explanation']) ? trim($questionData['explanation']) : null,
                        'difficulty' => $difficulty,
                    ]);
                    
                    $createdQuestions[] = $question;
                    
                } catch (\Exception $e) {
                    $errors[$index] = 'Error creating question: ' . $e->getMessage();
                    \Log::error("Error creating question {$index}: " . $e->getMessage());
                }
            }
            
            $totalCreated = count($createdQuestions);
            $totalErrors = count($errors);
            
            // Log completion for debugging, but don't set completion cache
            // The n8n completion endpoint will handle setting the final completion status
            \Log::info('Questions webhook processing completed', [
                'material_id' => $materialId,
                'questions_created' => $totalCreated,
                'total_errors' => $totalErrors,
                'total_questions_now' => $material->questions()->count()
            ]);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'material' => $material,
                    'questions_created' => $totalCreated,
                    'total_errors' => $totalErrors,
                    'questions' => $createdQuestions
                ],
                'message' => "{$totalCreated} questions created successfully" . ($totalErrors > 0 ? " ({$totalErrors} errors)" : "")
            ], 201);
            
        } catch (\Exception $e) {
            \Log::error('N8N webhook error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
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
     * Clear completion cache for a specific material
     */
    public function clearCompletionCache($materialId)
    {
        try {
            $material = Material::find($materialId);
            
            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material not found'
                ], 404);
            }
            
            $cacheKey = "n8n_completion_material_{$materialId}";
            cache()->forget($cacheKey);
            
            \Log::info('Manually cleared completion cache', [
                'material_id' => $materialId,
                'cache_key' => $cacheKey
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Completion cache cleared successfully',
                'data' => [
                    'material_id' => $materialId,
                    'cache_key' => $cacheKey,
                    'cleared_at' => now()->toISOString()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear completion cache: ' . $e->getMessage()
            ], 500);
        }
    }
}