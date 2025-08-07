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
                'tipe_soal' => $request->tipe_soal ?: 'pilihan_ganda',
                'option_a' => trim($request->options['A']),
                'option_b' => trim($request->options['B']),
                'option_c' => trim($request->options['C']),
                'option_d' => trim($request->options['D']),
                'option_e' => isset($request->options['E']) ? trim($request->options['E']) : null,
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
            
            // Get the raw input to handle potential markdown code blocks
            $rawInput = $request->getContent();
            $data = $request->all();
            
            // Check if the input is wrapped in markdown code blocks
            if (is_string($rawInput) && preg_match('/```json\s*(.*?)\s*```/s', $rawInput, $matches)) {
                \Log::info('Detected JSON code block format, extracting JSON...');
                try {
                    $extractedJson = trim($matches[1]);
                    $data = json_decode($extractedJson, true);
                    if (json_last_error() !== JSON_ERROR_NONE) {
                        throw new \Exception('Invalid JSON in code block: ' . json_last_error_msg());
                    }
                    \Log::info('Successfully extracted JSON from code block', ['data' => $data]);
                } catch (\Exception $e) {
                    \Log::error('Failed to parse JSON from code block: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid JSON format in code block: ' . $e->getMessage()
                    ], 400);
                }
            }
            
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
                    
                    // Normalize difficulty - Handle both Indonesian and English inputs
                    $difficulty = 'medium'; // default
                    if (isset($questionData['difficulty'])) {
                        // Comprehensive mapping for all difficulty formats
                        $difficultyMap = [
                            // Indonesian to English
                            'mudah' => 'easy',
                            'menengah' => 'medium', 
                            'sulit' => 'hard',
                            // English (lowercase)
                            'easy' => 'easy',
                            'medium' => 'medium',
                            'hard' => 'hard',
                            // English (capitalized) - for backwards compatibility
                            'Easy' => 'easy',
                            'Medium' => 'medium',
                            'Hard' => 'hard'
                        ];
                        
                        $inputDifficulty = trim($questionData['difficulty']);
                        $normalizedInput = strtolower($inputDifficulty);
                        
                        // Try exact match first (case-sensitive)
                        if (isset($difficultyMap[$inputDifficulty])) {
                            $difficulty = $difficultyMap[$inputDifficulty];
                        }
                        // Then try case-insensitive match
                        elseif (isset($difficultyMap[$normalizedInput])) {
                            $difficulty = $difficultyMap[$normalizedInput];
                        }
                        // Default fallback
                        else {
                            $difficulty = 'medium';
                            \Log::warning('Unknown difficulty level, defaulting to medium', [
                                'original' => $questionData['difficulty'],
                                'defaulted_to' => $difficulty
                            ]);
                        }
                        
                        \Log::debug('Difficulty processing', [
                            'original' => $questionData['difficulty'],
                            'processed' => $difficulty
                        ]);
                    }
                    
                    // Create the question
                    $question = Question::create([
                        'material_id' => $materialId,
                        'question' => trim($questionData['question']),
                        'tipe_soal' => $questionData['tipe_soal'] ?? 'pilihan_ganda',
                        'option_a' => trim($options['A']),
                        'option_b' => trim($options['B']),
                        'option_c' => trim($options['C']),
                        'option_d' => trim($options['D']),
                        'option_e' => isset($options['E']) && !empty($options['E']) ? trim($options['E']) : null,
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