<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class QuestionController extends Controller
{
    public function create(Request $request)
    {
        // Ambil data auto-fill dari session jika ada
        $autoFillData = session('auto_fill_data');
        
        // Clear session setelah diambil
        if ($autoFillData) {
            session()->forget('auto_fill_data');
        }
        
        // Check if material_id is provided in URL
        if ($request->has('material_id')) {
            $autoFillData = $autoFillData ?: [];
            $autoFillData['material_id'] = $request->material_id;
        }
        
        return view('form', compact('autoFillData'));
    }

public function store(Request $request)
{
    $request->validate([
        'material_id' => 'nullable|exists:materials,id',
        'question' => 'required|string',
        'option_a' => 'required|string',
        'option_b' => 'required|string',
        'option_c' => 'required|string',
        'option_d' => 'required|string',
        'option_e' => 'nullable|string',
        'answer' => 'required|in:A,B,C,D,E',
        'explanation' => 'nullable|string',
        'difficulty' => 'nullable|string',
    ]);

    // Simpan data soal ke dalam database using individual columns
    $question = Question::create([
        'material_id' => $request->material_id,
        'question' => $request->question,
        'tipe_soal' => $request->tipe_soal ?: 'pilihan_ganda',
        'option_a' => $request->option_a,
        'option_b' => $request->option_b,
        'option_c' => $request->option_c,
        'option_d' => $request->option_d,
        'option_e' => $request->option_e,
        'answer' => $request->answer,
        'explanation' => $request->explanation,
        'difficulty' => $request->difficulty,
    ]);

    // Redirect to material detail page if material_id exists, otherwise to questions.manage
    if ($request->material_id) {
        return redirect()->route('materials.show', $request->material_id)->with('success', 'Soal berhasil disimpan!');
    }
    
    return redirect()->route('questions.manage')->with('success', 'Soal berhasil disimpan!');
}


    public function index()
    {
        $questions = Question::latest()->get()->map(function ($question) {
            return [
                'question' => $question->question,
                'options' => $question->options,
                'answer' => $question->answer,
                'explanation' => $question->explanation,
            ];
        });
        
        return response()->json([
            'soal' => $questions
        ]);
    }

    public function storeFromApi(Request $request)
    {
        // Debug: Log data yang diterima
        \Log::info('Data received from n8n:', $request->all());
        \Log::info('Raw input:', $request->getContent());
        
        // Cek apakah data dikirim sebagai string JSON dengan escape characters
        $rawContent = $request->getContent();
        
        // Jika content adalah string JSON yang di-escape, decode dulu
        if (is_string($rawContent) && str_contains($rawContent, '\\n')) {
            // Remove escape characters dan decode JSON
            $cleanedContent = str_replace(['\n', '\"'], ["\n", '"'], $rawContent);
            $cleanedContent = trim($cleanedContent, '"'); // Remove outer quotes if any
            
            try {
                $decodedData = json_decode($cleanedContent, true);
                if ($decodedData) {
                    // Auto-detect format dan extract questions
                    $questions = $this->extractQuestionsFromData($decodedData);
                    
                    if (!empty($questions)) {
                        // Jika multiple questions, proses sebagai batch
                        if (count($questions) > 1) {
                            return $this->processBatchQuestions($questions);
                        }
                        // Jika single question, merge ke request
                        $request->merge($questions[0]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('JSON decode error:', ['error' => $e->getMessage()]);
            }
        }
        
        // Check if this is batch data (multiple questions)
        $allData = $request->all();
        $questions = $this->extractQuestionsFromData($allData);
        
        if (count($questions) > 1) {
            return $this->processBatchQuestions($questions);
        }
        
        // Single question validation
        $request->validate([
            'material_id' => 'nullable|exists:materials,id',
            'question' => 'required|string',
            'options' => 'required|array',
            'options.A' => 'required|string',
            'options.B' => 'required|string', 
            'options.C' => 'required|string',
            'options.D' => 'required|string',
            'options.E' => 'nullable|string',
            'answer' => 'required|in:A,B,C,D,E',
            'explanation' => 'nullable|string',
            'difficulty' => 'nullable|string',
        ]);

        $options = $request->options;
        $question = Question::create([
            'material_id' => $request->material_id,
            'question' => $request->question,
            'tipe_soal' => $request->tipe_soal ?: 'pilihan_ganda',
            'option_a' => $options['A'],
            'option_b' => $options['B'],
            'option_c' => $options['C'],
            'option_d' => $options['D'],
            'option_e' => $options['E'] ?? null,
            'answer' => $request->answer,
            'explanation' => $request->explanation,
            'difficulty' => $request->difficulty,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Soal berhasil disimpan!',
            'data' => [
                'question' => $question->question,
                'options' => $question->options,
                'answer' => $question->answer,
                'explanation' => $question->explanation,
            ]
        ], 201);
    }
    
    /**
     * Process multiple questions at once
     */
    private function processBatchQuestions($questions)
    {
        $savedQuestions = [];
        $errors = [];
        
        foreach ($questions as $index => $questionData) {
            try {
                $validator = \Validator::make($questionData, [
                    'material_id' => 'nullable|exists:materials,id',
                    'question' => 'required|string',
                    'options' => 'required|array',
                    'options.A' => 'required|string',
                    'options.B' => 'required|string', 
                    'options.C' => 'required|string',
                    'options.D' => 'required|string',
                    'options.E' => 'nullable|string',
                    'answer' => 'required|in:A,B,C,D,E',
                    'explanation' => 'nullable|string',
                    'difficulty' => 'nullable|string',
                ]);
                
                if ($validator->fails()) {
                    $errors[$index] = $validator->errors();
                    continue;
                }
                
                $options = $questionData['options'];
                $question = Question::create([
                    'material_id' => $questionData['material_id'] ?? null,
                    'question' => $questionData['question'],
                    'tipe_soal' => $questionData['tipe_soal'] ?? 'pilihan_ganda',
                    'option_a' => $options['A'],
                    'option_b' => $options['B'],
                    'option_c' => $options['C'],
                    'option_d' => $options['D'],
                    'option_e' => $options['E'] ?? null,
                    'answer' => $questionData['answer'],
                    'explanation' => $questionData['explanation'] ?? null,
                    'difficulty' => $questionData['difficulty'] ?? null,
                ]);
                
                $savedQuestions[] = [
                    'id' => $question->id,
                    'question' => $question->question,
                    'options' => $question->options, // This will use the accessor
                    'answer' => $question->answer,
                    'explanation' => $question->explanation,
                ];
                
            } catch (\Exception $e) {
                $errors[$index] = ['error' => $e->getMessage()];
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => count($savedQuestions) . ' soal berhasil disimpan!',
            'data' => $savedQuestions,
            'errors' => $errors,
            'total_processed' => count($questions),
            'total_saved' => count($savedQuestions),
            'total_errors' => count($errors)
        ], 201);
    }

    public function storeFromRawJson(Request $request)
    {
        // Method khusus untuk menangani JSON string dengan escape characters
        $rawContent = $request->getContent();
        \Log::info('Raw JSON received:', [
            'content' => $rawContent,
            'headers' => $request->headers->all(),
            'method' => $request->method(),
            'all_data' => $request->all()
        ]);
        
        // Jika raw content kosong, coba ambil dari request data
        if (empty($rawContent)) {
            $allData = $request->all();
            if (!empty($allData)) {
                \Log::info('Using request data instead of raw content:', $allData);
                $data = $allData;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No data received',
                    'debug' => [
                        'raw_content' => $rawContent,
                        'request_data' => $request->all(),
                        'headers' => $request->headers->all()
                    ]
                ], 400);
            }
        } else {
            // Clean escape characters
            $cleanedContent = str_replace(['\n', '\"'], ["\n", '"'], $rawContent);
            $cleanedContent = trim($cleanedContent, '"'); // Remove outer quotes
            
            try {
                $data = json_decode($cleanedContent, true);
                
                if (!$data) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid JSON format',
                        'debug' => [
                            'raw_content' => $rawContent,
                            'cleaned_content' => $cleanedContent,
                            'json_error' => json_last_error_msg()
                        ]
                    ], 400);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'JSON parsing error: ' . $e->getMessage(),
                    'debug' => [
                        'raw_content' => $rawContent,
                        'cleaned_content' => $cleanedContent ?? null
                    ]
                ], 400);
            }
        }
        
        // Auto-detect format dan extract questions
        $questions = $this->extractQuestionsFromData($data);
        
        if (empty($questions)) {
            return response()->json([
                'success' => false,
                'message' => 'No valid questions found in the data',
                'debug' => [
                    'received_data' => $data,
                    'data_type' => gettype($data),
                    'is_array' => is_array($data),
                    'first_element' => is_array($data) && !empty($data) ? $data[0] : null
                ]
            ], 400);
        }
        
        return $this->processBatchQuestions($questions);
    }
    
    public function storeFromJsonArray(Request $request)
    {
        // Method khusus untuk menangani JSON array string dengan escape characters dari n8n
        $rawContent = $request->getContent();
        \Log::info('JSON Array received:', [
            'content' => $rawContent,
            'content_type' => $request->header('Content-Type'),
            'method' => $request->method()
        ]);
        
        if (empty($rawContent)) {
            return response()->json([
                'success' => false,
                'message' => 'No data received - raw content is empty',
                'debug' => [
                    'headers' => $request->headers->all(),
                    'request_data' => $request->all()
                ]
            ], 400);
        }
        
        try {
            // Handle escape characters yang umum dari n8n
            $cleanedContent = $rawContent;
            
            // Remove escape characters \n menjadi newline sebenarnya
            $cleanedContent = str_replace('\\n', "\n", $cleanedContent);
            
            // Remove escape characters \" menjadi quote sebenarnya  
            $cleanedContent = str_replace('\\"', '"', $cleanedContent);
            
            // Trim outer quotes jika ada
            $cleanedContent = trim($cleanedContent, '"');
            
            \Log::info('Cleaned content:', ['cleaned' => $cleanedContent]);
            
            // Parse JSON
            $data = json_decode($cleanedContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON: ' . json_last_error_msg(),
                    'debug' => [
                        'raw_content' => $rawContent,
                        'cleaned_content' => $cleanedContent,
                        'json_error' => json_last_error_msg()
                    ]
                ], 400);
            }
            
            // Pastikan data adalah array
            if (!is_array($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Expected array of questions, got: ' . gettype($data),
                    'debug' => [
                        'data_type' => gettype($data),
                        'data' => $data
                    ]
                ], 400);
            }
            
            // Jika array kosong
            if (empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Array is empty',
                    'debug' => ['data' => $data]
                ], 400);
            }
            
            // Validate setiap item dalam array adalah question object
            foreach ($data as $index => $item) {
                if (!is_array($item) || !isset($item['question']) || !isset($item['options'])) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid question format at index {$index}",
                        'debug' => [
                            'item' => $item,
                            'index' => $index
                        ]
                    ], 400);
                }
            }
            
            \Log::info('Successfully parsed ' . count($data) . ' questions');
            
            // Process questions
            return $this->processBatchQuestions($data);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing JSON array: ' . $e->getMessage(),
                'debug' => [
                    'raw_content' => $rawContent,
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]
            ], 500);
        }
    }
    
    /**
     * Auto-detect dan extract questions dari berbagai format JSON
     */
    private function extractQuestionsFromData($data)
    {
        $questions = [];
        
        // Format 1: Direct array of questions - ini yang paling umum dari n8n
        if (is_array($data) && isset($data[0]) && is_array($data[0]) && isset($data[0]['question'])) {
            \Log::info('Detected format: Direct array of questions');
            return $data;
        }
        
        // Format 2: Object dengan key 'soal'
        if (isset($data['soal']) && is_array($data['soal'])) {
            \Log::info('Detected format: Object with soal key');
            return $data['soal'];
        }
        
        // Format 3: Object dengan key 'quiz' 
        if (isset($data['quiz']) && is_array($data['quiz'])) {
            \Log::info('Detected format: Object with quiz key');
            return $data['quiz'];
        }
        
        // Format 4: Object dengan key 'questions'
        if (isset($data['questions']) && is_array($data['questions'])) {
            \Log::info('Detected format: Object with questions key');
            return $data['questions'];
        }
        
        // Format 5: Object dengan key 'items'
        if (isset($data['items']) && is_array($data['items'])) {
            \Log::info('Detected format: Object with items key');
            return $data['items'];
        }
        
        // Format 6: Object dengan key 'data'
        if (isset($data['data']) && is_array($data['data'])) {
            \Log::info('Detected format: Object with data key');
            return $data['data'];
        }
        
        // Format 7: Single question object
        if (isset($data['question']) && isset($data['options'])) {
            \Log::info('Detected format: Single question object');
            return [$data];
        }
        
        // Format 8: Coba deteksi otomatis dari semua key yang berupa array
        foreach ($data as $key => $value) {
            if (is_array($value) && !empty($value) && is_array($value[0]) && isset($value[0]['question'])) {
                \Log::info('Detected format: Auto-detected array with key: ' . $key);
                return $value;
            }
        }
        
        \Log::warning('No valid format detected', [
            'data_type' => gettype($data),
            'is_array' => is_array($data),
            'keys' => is_array($data) ? array_keys($data) : null,
            'first_element' => is_array($data) && !empty($data) ? $data[0] : null
        ]);
        
        return [];
    }
    
    /**
     * Fill form dengan data dari JSON - form tetap submit manual
     */
    public function fillForm(Request $request)
    {
        try {
            $rawContent = $request->getContent();
            \Log::info('Fill Form - Debug Info:', [
                'raw_content' => $rawContent,
                'request_all' => $request->all(),
                'headers' => $request->headers->all(),
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'has_json' => $request->json() !== null
            ]);
            
            $data = null;
            
            // Coba beberapa cara untuk mendapatkan data
            
            // Method 1: Dari request json() terlebih dahulu (paling common)
            try {
                if ($request->json() !== null) {
                    \Log::info('Trying method 1: Request json');
                    $jsonData = $request->json()->all();
                    if (!empty($jsonData)) {
                        $data = $jsonData;
                        \Log::info('Method 1 successful', ['data' => $data]);
                    }
                }
            } catch (\Exception $e) {
                \Log::info('Method 1 failed: ' . $e->getMessage());
            }
            
            // Method 2: Dari request all()
            if (!$data) {
                try {
                    \Log::info('Trying method 2: Request all');
                    $allData = $request->all();
                    if (!empty($allData)) {
                        $data = $allData;
                        \Log::info('Method 2 successful', ['data' => $data]);
                    }
                } catch (\Exception $e) {
                    \Log::info('Method 2 failed: ' . $e->getMessage());
                }
            }
            
            // Method 3: Dari raw content dengan cleaning
            if (!$data && !empty($rawContent)) {
                try {
                    \Log::info('Trying method 3: Raw content parsing');
                    
                    // Coba parse langsung
                    $data = json_decode($rawContent, true);
                    
                    if (!$data) {
                        // Coba dengan cleaning escape characters
                        $cleanedContent = str_replace(['\\n', '\\"', '\\\\'], ["\n", '"', '\\'], $rawContent);
                        $cleanedContent = trim($cleanedContent, '"');
                        $data = json_decode($cleanedContent, true);
                    }
                    
                    if (!$data) {
                        // Coba dengan urldecode jika data ter-encode
                        $decodedContent = urldecode($rawContent);
                        $data = json_decode($decodedContent, true);
                    }
                    
                    if ($data) {
                        \Log::info('Method 3 successful', ['data' => $data]);
                    }
                } catch (\Exception $e) {
                    \Log::info('Method 3 failed: ' . $e->getMessage());
                }
            }
            
            // Method 4: Coba ambil dari parameter tertentu
            if (!$data) {
                try {
                    \Log::info('Trying method 4: Specific parameters');
                    $possibleKeys = ['data', 'questions', 'soal', 'quiz', 'body', 'payload'];
                    
                    foreach ($possibleKeys as $key) {
                        if ($request->has($key)) {
                            $value = $request->input($key);
                            if (is_string($value)) {
                                $data = json_decode($value, true);
                            } else {
                                $data = $value;
                            }
                            
                            if ($data) {
                                \Log::info('Method 4 successful with key: ' . $key, ['data' => $data]);
                                break;
                            }
                        }
                    }
                } catch (\Exception $e) {
                    \Log::info('Method 4 failed: ' . $e->getMessage());
                }
            }
            
            if (!$data) {
                \Log::error('All parsing methods failed', [
                    'raw_content' => $rawContent,
                    'request_all' => $request->all(),
                    'headers' => $request->headers->all()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'No data received for filling form',
                    'debug' => [
                        'raw_content' => $rawContent,
                        'request_all' => $request->all(),
                        'json_decode_error' => json_last_error_msg(),
                        'headers' => $request->headers->all(),
                        'methods_tried' => ['json()', 'all()', 'raw_content', 'specific_parameters']
                    ]
                ], 400);
            }
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON: ' . json_last_error_msg(),
                    'debug' => ['data' => $data]
                ], 400);
            }
            
            // Extract questions using existing method
            $questions = $this->extractQuestionsFromData($data);
            
            if (empty($questions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No questions found in data',
                    'debug' => [
                        'received_data' => $data,
                        'data_type' => gettype($data)
                    ]
                ], 400);
            }
            
            // Ambil question pertama untuk mengisi form
            $questionData = $questions[0];
            
            // Validate structure
            if (!isset($questionData['question']) || !isset($questionData['options'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid question structure',
                    'debug' => ['question_data' => $questionData]
                ], 400);
            }
            
            // Format data untuk form
            $formData = [
                'question' => $questionData['question'],
                'option_a' => $questionData['options']['A'] ?? '',
                'option_b' => $questionData['options']['B'] ?? '',
                'option_c' => $questionData['options']['C'] ?? '',
                'option_d' => $questionData['options']['D'] ?? '',
                'answer' => $questionData['answer'] ?? '',
                'explanation' => $questionData['explanation'] ?? '',
            ];
            
            // Store dalam session untuk digunakan oleh form
            session(['auto_fill_data' => $formData]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disiapkan untuk mengisi form',
                'data' => $formData,
                'total_questions' => count($questions),
                'form_url' => route('questions.create')
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing data: ' . $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]
            ], 500);
        }
    }
    
    /**
     * Test endpoint untuk debugging
     */
    public function testEndpoint(Request $request)
    {
        $rawContent = $request->getContent();
        $jsonData = null;
        $allData = $request->all();
        
        // Coba parse JSON dari berbagai cara
        try {
            $jsonData = $request->json() ? $request->json()->all() : null;
        } catch (\Exception $e) {
            $jsonData = 'Error: ' . $e->getMessage();
        }
        
        // Coba parse raw content
        $rawParsed = null;
        if (!empty($rawContent)) {
            $rawParsed = json_decode($rawContent, true);
            if (!$rawParsed) {
                // Coba dengan cleaning
                $cleanedContent = str_replace(['\\n', '\\"', '\\\\'], ["\n", '"', '\\'], $rawContent);
                $cleanedContent = trim($cleanedContent, '"');
                $rawParsed = json_decode($cleanedContent, true);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Test endpoint berhasil',
            'debug' => [
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'raw_content' => $rawContent,
                'raw_content_length' => strlen($rawContent),
                'raw_parsed' => $rawParsed,
                'json_last_error' => json_last_error_msg(),
                'request_all' => $allData,
                'request_json' => $jsonData,
                'has_json' => $request->json() !== null,
                'query_params' => $request->query->all(),
                'post_params' => $request->request->all(),
                'headers' => $request->headers->all(),
                'server' => [
                    'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? null,
                    'CONTENT_TYPE' => $_SERVER['CONTENT_TYPE'] ?? null,
                    'CONTENT_LENGTH' => $_SERVER['CONTENT_LENGTH'] ?? null,
                ]
            ],
            'recommendations' => [
                'use_content_type' => 'application/json',
                'ensure_valid_json' => 'Make sure JSON is properly formatted',
                'check_encoding' => 'UTF-8 encoding recommended'
            ]
        ], 200);
    }
    
    /**
     * Endpoint khusus untuk n8n yang lebih fleksibel
     */
    public function fillFormFromN8n(Request $request)
    {
        try {
            \Log::info('N8N Fill Form Request:', [
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'raw_content' => $request->getContent(),
                'content_length' => $request->header('Content-Length'),
                'content_type' => $request->header('Content-Type'),
                'query_params' => $request->query->all(),
                'all_input' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            $data = null;
            
            // Method 1: Cek query parameters (untuk GET requests atau URL parameters)
            $queryData = $request->query->all();
            if (!empty($queryData)) {
                \Log::info('Found data in query parameters');
                if (isset($queryData['data'])) {
                    $data = is_string($queryData['data']) ? json_decode($queryData['data'], true) : $queryData['data'];
                } else {
                    $data = $queryData;
                }
            }
            
            // Method 2: Cek form data (application/x-www-form-urlencoded)
            if (!$data) {
                $formData = $request->request->all();
                if (!empty($formData)) {
                    \Log::info('Found data in form parameters');
                    if (isset($formData['data'])) {
                        $data = is_string($formData['data']) ? json_decode($formData['data'], true) : $formData['data'];
                    } else {
                        $data = $formData;
                    }
                }
            }
            
            // Method 3: Cek JSON body
            if (!$data) {
                try {
                    $jsonData = $request->json()->all();
                    if (!empty($jsonData)) {
                        \Log::info('Found data in JSON body');
                        $data = $jsonData;
                    }
                } catch (\Exception $e) {
                    \Log::info('JSON parsing failed: ' . $e->getMessage());
                }
            }
            
            // Method 4: Cek raw content dengan berbagai format
            if (!$data) {
                $rawContent = $request->getContent();
                if (!empty($rawContent)) {
                    \Log::info('Trying to parse raw content');
                    
                    // Coba parse sebagai JSON
                    $data = json_decode($rawContent, true);
                    
                    if (!$data) {
                        // Coba parse sebagai URL encoded
                        parse_str($rawContent, $parsed);
                        if (!empty($parsed)) {
                            $data = $parsed;
                        }
                    }
                    
                    if (!$data) {
                        // Coba dengan cleaning escape characters
                        $cleanedContent = str_replace(['\\n', '\\"', '\\\\'], ["\n", '"', '\\'], $rawContent);
                        $cleanedContent = trim($cleanedContent, '"');
                        $data = json_decode($cleanedContent, true);
                    }
                }
            }
            
            // Method 5: Jika masih belum ada data, coba ambil dari headers khusus
            if (!$data) {
                // Beberapa sistem mengirim data via custom headers
                $customHeaders = ['X-Data', 'X-Payload', 'X-Questions', 'X-Body'];
                foreach ($customHeaders as $header) {
                    $headerValue = $request->header($header);
                    if ($headerValue) {
                        \Log::info('Found data in header: ' . $header);
                        $data = json_decode($headerValue, true);
                        if ($data) break;
                    }
                }
            }
            
            // Method 6: Fallback untuk n8n - coba ambil dari input dengan key tertentu
            if (!$data) {
                $possibleKeys = ['json', 'body', 'payload', 'questions', 'soal', 'quiz', 'data'];
                foreach ($possibleKeys as $key) {
                    $value = $request->input($key);
                    if ($value) {
                        \Log::info('Found data with key: ' . $key);
                        $data = is_string($value) ? json_decode($value, true) : $value;
                        if ($data) break;
                    }
                }
            }
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data received from n8n. Please check your n8n configuration.',
                    'debug' => [
                        'content_length' => $request->header('Content-Length'),
                        'content_type' => $request->header('Content-Type'),
                        'raw_content' => $request->getContent(),
                        'query_params' => $request->query->all(),
                        'form_data' => $request->request->all(),
                        'headers' => $request->headers->all(),
                        'user_agent' => $request->userAgent(),
                        'ip' => $request->ip()
                    ],
                    'suggestions' => [
                        'check_n8n_http_node_config' => 'Ensure HTTP Request node in n8n is configured properly',
                        'set_content_type' => 'Set Content-Type to application/json in n8n',
                        'verify_body_data' => 'Make sure body contains the question data',
                        'try_different_methods' => 'Try GET with query parameters or POST with form data'
                    ]
                ], 400);
            }
            
            \Log::info('Successfully extracted data', ['data' => $data]);
            
            // Extract questions menggunakan method yang sudah ada
            $questions = $this->extractQuestionsFromData($data);
            
            if (empty($questions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No questions found in the received data',
                    'debug' => [
                        'received_data' => $data,
                        'data_type' => gettype($data),
                        'data_keys' => is_array($data) ? array_keys($data) : 'not_array'
                    ]
                ], 400);
            }
            
            // Ambil question pertama untuk mengisi form
            $questionData = $questions[0];
            
            // Validate structure
            if (!isset($questionData['question']) || !isset($questionData['options'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid question structure',
                    'debug' => ['question_data' => $questionData]
                ], 400);
            }
            
            // Format data untuk form
            $formData = [
                'question' => $questionData['question'],
                'option_a' => $questionData['options']['A'] ?? '',
                'option_b' => $questionData['options']['B'] ?? '',
                'option_c' => $questionData['options']['C'] ?? '',
                'option_d' => $questionData['options']['D'] ?? '',
                'answer' => $questionData['answer'] ?? '',
                'explanation' => $questionData['explanation'] ?? '',
            ];
            
            // Store dalam session untuk digunakan oleh form
            session(['auto_fill_data' => $formData]);
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disiapkan untuk mengisi form dari n8n',
                'data' => $formData,
                'total_questions' => count($questions),
                'form_url' => route('questions.create'),
                'debug' => [
                    'source_method' => 'n8n_flexible_parser',
                    'data_source' => 'determined_automatically'
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error processing data from n8n: ' . $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile(),
                    'trace' => $e->getTraceAsString()
                ]
            ], 500);
        }
    }
    
    /**
     * Auto save multiple questions dari n8n langsung ke database
     */
    public function autoSaveFromN8n(Request $request)
    {
        try {
            \Log::info('N8N Auto Save Request:', [
                'method' => $request->method(),
                'headers' => $request->headers->all(),
                'raw_content' => $request->getContent(),
                'content_length' => $request->header('Content-Length'),
                'content_type' => $request->header('Content-Type'),
                'query_params' => $request->query->all(),
                'all_input' => $request->all(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent()
            ]);
            
            $data = null;
            
            // Method 1: Cek query parameters
            $queryData = $request->query->all();
            if (!empty($queryData)) {
                \Log::info('Found data in query parameters');
                if (isset($queryData['data'])) {
                    $data = is_string($queryData['data']) ? json_decode($queryData['data'], true) : $queryData['data'];
                } else {
                    $data = $queryData;
                }
            }
            
            // Method 2: Cek form data
            if (!$data) {
                $formData = $request->request->all();
                if (!empty($formData)) {
                    \Log::info('Found data in form parameters');
                    if (isset($formData['data'])) {
                        $data = is_string($formData['data']) ? json_decode($formData['data'], true) : $formData['data'];
                    } else {
                        $data = $formData;
                    }
                }
            }
            
            // Method 3: Cek JSON body
            if (!$data) {
                try {
                    $jsonData = $request->json()->all();
                    if (!empty($jsonData)) {
                        \Log::info('Found data in JSON body');
                        $data = $jsonData;
                    }
                } catch (\Exception $e) {
                    \Log::info('JSON parsing failed: ' . $e->getMessage());
                }
            }
            
            // Method 4: Cek raw content
            if (!$data) {
                $rawContent = $request->getContent();
                if (!empty($rawContent)) {
                    \Log::info('Trying to parse raw content');
                    
                    // Coba parse sebagai JSON
                    $data = json_decode($rawContent, true);
                    
                    if (!$data) {
                        // Coba dengan cleaning escape characters
                        $cleanedContent = str_replace(['\\n', '\\"', '\\\\'], ["\n", '"', '\\'], $rawContent);
                        $cleanedContent = trim($cleanedContent, '"');
                        $data = json_decode($cleanedContent, true);
                    }
                }
            }
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data received from n8n for auto-save.',
                    'debug' => [
                        'content_length' => $request->header('Content-Length'),
                        'content_type' => $request->header('Content-Type'),
                        'raw_content' => $request->getContent(),
                        'query_params' => $request->query->all(),
                        'form_data' => $request->request->all(),
                        'headers' => $request->headers->all()
                    ]
                ], 400);
            }
            
            \Log::info('Successfully extracted data for auto-save', ['data' => $data]);
            
            // Extract questions menggunakan method yang sudah ada
            $questions = $this->extractQuestionsFromData($data);
            
            if (empty($questions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No questions found in the received data',
                    'debug' => [
                        'received_data' => $data,
                        'data_type' => gettype($data)
                    ]
                ], 400);
            }
            
            // Process dan save semua questions
            $result = $this->processBatchQuestions($questions);
            
            // Convert response untuk auto-save format
            $responseData = json_decode($result->getContent(), true);
            
            if ($responseData['success']) {
                // Broadcast event untuk real-time update (optional)
                // event(new QuestionsCreated($responseData['data']));
                
                return response()->json([
                    'success' => true,
                    'message' => 'All questions automatically saved from n8n',
                    'total_questions' => $responseData['total_saved'],
                    'saved_questions' => $responseData['data'],
                    'errors' => $responseData['errors'],
                    'view_url' => route('questions.manage'),
                    'create_url' => route('questions.create'),
                    'debug' => [
                        'source' => 'n8n_auto_save',
                        'processed' => $responseData['total_processed'],
                        'saved' => $responseData['total_saved'],
                        'errors_count' => $responseData['total_errors'],
                        'timestamp' => now()->toISOString()
                    ]
                ], 201);
            } else {
                return $result;
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error auto-saving data from n8n: ' . $e->getMessage(),
                'debug' => [
                    'error' => $e->getMessage(),
                    'line' => $e->getLine(),
                    'file' => $e->getFile()
                ]
            ], 500);
        }
    }
    
    /**
     * Manage questions - show all saved questions with edit capability
     */
    public function manage()
    {
        $questions = Question::with('material')->latest()->paginate(10);
        return view('questions.manage', compact('questions'));
    }
    
    /**
     * Edit specific question
     */
    public function edit($id)
    {
        $question = Question::findOrFail($id);
        $materials = \App\Models\Material::where('is_active', true)->orderBy('title')->get();
        return view('questions.edit', compact('question', 'materials'));
    }
    
    /**
     * Update specific question
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'material_id' => 'nullable|exists:materials,id',
            'question' => 'required|string',
            'option_a' => 'required|string',
            'option_b' => 'required|string',
            'option_c' => 'required|string',
            'option_d' => 'required|string',
            'option_e' => 'nullable|string',
            'answer' => 'required|in:A,B,C,D,E',
            'explanation' => 'nullable|string',
            'difficulty' => 'nullable|string',
        ]);

        $question = Question::findOrFail($id);
        
        $question->update([
            'material_id' => $request->material_id,
            'question' => $request->question,
            'option_A' => $request->option_a,
            'option_B' => $request->option_b,
            'option_C' => $request->option_c,
            'option_D' => $request->option_d,
            'option_E' => $request->option_e,
            'answer' => $request->answer,
            'explanation' => $request->explanation,
            'difficulty' => $request->difficulty,
        ]);

        // Redirect to material detail page if material_id exists, otherwise to questions.manage
        if ($request->material_id) {
            return redirect()->route('materials.show', $request->material_id)->with('success', 'Soal berhasil diupdate!');
        }
        
        return redirect()->route('questions.manage')->with('success', 'Soal berhasil diupdate!');
    }
    
    /**
     * Delete specific question
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();
        
        return redirect()->back()->with('success', 'Soal berhasil dihapus!');
    }
    
    /**
     * Endpoint khusus untuk n8n dengan robust error handling
     */
    public function n8nEndpoint(Request $request)
    {
        try {
            // Log semua info yang bisa membantu debugging
            $rawContent = $request->getContent();
            $contentType = $request->header('Content-Type');
            $userAgent = $request->header('User-Agent');
            
            \Log::info('N8N Endpoint called:', [
                'method' => $request->method(),
                'content_type' => $contentType,
                'user_agent' => $userAgent,
                'raw_content_length' => strlen($rawContent),
                'raw_content_preview' => substr($rawContent, 0, 500),
                'headers' => $request->headers->all(),
                'request_data' => $request->all()
            ]);

            // Coba berbagai cara mendapatkan data
            $data = null;
            $parseMethod = 'unknown';

            // Method 1: Dari raw content sebagai JSON
            if (!empty($rawContent)) {
                try {
                    $data = json_decode($rawContent, true);
                    if ($data !== null) {
                        $parseMethod = 'raw_json';
                        \Log::info('Successfully parsed from raw JSON');
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to parse raw content as JSON: ' . $e->getMessage());
                }
            }

            // Method 2: Dari form data
            if ($data === null) {
                $requestData = $request->all();
                if (!empty($requestData)) {
                    $data = $requestData;
                    $parseMethod = 'form_data';
                    \Log::info('Using form data:', $data);
                }
            }

            // Method 3: Coba decode jika ada field 'json' di form data
            if ($data === null || (is_array($data) && isset($data['json']))) {
                $jsonField = $request->input('json');
                if ($jsonField) {
                    try {
                        $decoded = json_decode($jsonField, true);
                        if ($decoded !== null) {
                            $data = $decoded;
                            $parseMethod = 'json_field';
                            \Log::info('Successfully parsed from json field');
                        }
                    } catch (\Exception $e) {
                        \Log::warning('Failed to parse json field: ' . $e->getMessage());
                    }
                }
            }

            // Jika masih belum dapat data
            if ($data === null || empty($data)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid data received',
                    'error_code' => 'NO_DATA',
                    'debug' => [
                        'raw_content_length' => strlen($rawContent),
                        'raw_content_preview' => substr($rawContent, 0, 200),
                        'content_type' => $contentType,
                        'request_data' => $request->all(),
                        'json_last_error' => json_last_error_msg()
                    ],
                    'suggestions' => [
                        'Make sure Content-Type is application/json',
                        'Check that JSON data is properly formatted',
                        'Verify that data is sent in request body',
                        'Try sending data as form field named "json"'
                    ]
                ], 400);
            }

            \Log::info("Data parsed using method: {$parseMethod}", ['data' => $data]);

            // Extract questions dari data
            $questions = [];
            try {
                $questions = $this->extractQuestionsFromData($data);
            } catch (\Exception $e) {
                \Log::error('Error extracting questions: ' . $e->getMessage());
                $questions = [];
            }
            
            if (empty($questions)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid questions found in data',
                    'error_code' => 'NO_QUESTIONS',
                    'debug' => [
                        'parse_method' => $parseMethod,
                        'data_received' => $data,
                        'data_type' => gettype($data),
                        'data_keys' => is_array($data) ? array_keys($data) : null,
                        'data_count' => is_array($data) ? count($data) : null
                    ],
                    'expected_format' => [
                        'Direct array' => [
                            ['question' => 'Q1', 'options' => ['A' => 'opt1', 'B' => 'opt2', 'C' => 'opt3', 'D' => 'opt4'], 'answer' => 'A'],
                            ['question' => 'Q2', 'options' => ['A' => 'opt1', 'B' => 'opt2', 'C' => 'opt3', 'D' => 'opt4'], 'answer' => 'B']
                        ],
                        'Object with soal key' => [
                            'soal' => [
                                ['question' => 'Q1', 'options' => ['A' => 'opt1', 'B' => 'opt2', 'C' => 'opt3', 'D' => 'opt4'], 'answer' => 'A']
                            ]
                        ]
                    ]
                ], 400);
            }

            // Validasi setiap question
            foreach ($questions as $index => $question) {
                $validation = $this->validateQuestionData($question);
                if (!$validation['valid']) {
                    return response()->json([
                        'success' => false,
                        'message' => "Invalid question at index {$index}: " . $validation['message'],
                        'error_code' => 'INVALID_QUESTION',
                        'debug' => [
                            'question_index' => $index,
                            'question_data' => $question,
                            'validation_error' => $validation['message']
                        ]
                    ], 400);
                }
            }

            // Simpan questions ke database
            $savedCount = 0;
            $errors = [];

            foreach ($questions as $index => $questionData) {
                try {
                    Question::create([
                        'material_id' => $questionData['material_id'] ?? null,
                        'question' => $questionData['question'],
                        'options' => $questionData['options'],
                        'answer' => $questionData['answer'],
                        'explanation' => $questionData['explanation'] ?? null,
                        'difficulty' => $questionData['difficulty'] ?? null,
                    ]);
                    $savedCount++;
                } catch (\Exception $e) {
                    $errors[] = "Question {$index}: " . $e->getMessage();
                    \Log::error("Failed to save question {$index}: " . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully processed {$savedCount} questions",
                'data' => [
                    'total_questions' => count($questions),
                    'saved_count' => $savedCount,
                    'parse_method' => $parseMethod,
                    'errors' => $errors
                ]
            ]);

        } catch (\Exception $e) {
            \Log::error('N8N Endpoint Error: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'error_code' => 'SERVER_ERROR',
                'debug' => [
                    'error_line' => $e->getLine(),
                    'error_file' => basename($e->getFile())
                ]
            ], 500);
        }
    }

    /**
     * Validasi data question
     */
    private function validateQuestionData($questionData)
    {
        if (!is_array($questionData)) {
            return ['valid' => false, 'message' => 'Question data must be an array'];
        }

        if (!isset($questionData['question']) || empty($questionData['question'])) {
            return ['valid' => false, 'message' => 'Question text is required'];
        }

        if (!isset($questionData['options']) || !is_array($questionData['options'])) {
            return ['valid' => false, 'message' => 'Options must be an array'];
        }

        $requiredOptions = ['A', 'B', 'C', 'D'];
        foreach ($requiredOptions as $option) {
            if (!isset($questionData['options'][$option]) || empty($questionData['options'][$option])) {
                return ['valid' => false, 'message' => "Option {$option} is required"];
            }
        }

        if (!isset($questionData['answer']) || !in_array($questionData['answer'], $requiredOptions)) {
            return ['valid' => false, 'message' => 'Answer must be A, B, C, or D'];
        }

        return ['valid' => true, 'message' => 'Valid question data'];
    }
    
    /**
     * Simple ping endpoint untuk test koneksi dari n8n
     */
    public function ping(Request $request)
    {
        return response()->json([
            'success' => true,
            'message' => 'Server is reachable',
            'timestamp' => now()->toISOString(),
            'method' => $request->method(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'headers' => $request->headers->all()
        ]);
    }

    /**
     * Echo endpoint - mengembalikan data yang dikirim
     */
    public function echo(Request $request)
    {
        $rawContent = $request->getContent();
        
        return response()->json([
            'success' => true,
            'message' => 'Data received successfully',
            'echo' => [
                'method' => $request->method(),
                'raw_content' => $rawContent,
                'raw_content_length' => strlen($rawContent),
                'content_type' => $request->header('Content-Type'),
                'request_data' => $request->all(),
                'json_decode_attempt' => json_decode($rawContent, true),
                'json_last_error' => json_last_error_msg()
            ]
        ]);
    }
}
