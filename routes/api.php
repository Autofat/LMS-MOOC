<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\Api\MaterialApiController;
use App\Http\Controllers\Api\QuestionApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes for n8n (no authentication required)
Route::prefix('public')->group(function () {
    // PDF download for n8n without authentication
    Route::get('/materials/{id}/download', [MaterialController::class, 'apiDownload']);
    Route::get('/materials/{id}/file-content', [MaterialController::class, 'getFileForN8n']);
    Route::get('/materials/{id}/stream', [MaterialController::class, 'streamFileForN8n']);
    
    // Question saving for n8n without authentication
    Route::post('/questions/auto-save', [QuestionApiController::class, 'webhook']);
    Route::post('/questions/webhook', [QuestionApiController::class, 'webhook']);
});

// Material API Routes
Route::prefix('materials')->middleware(['throttle:60,1'])->group(function () {
    Route::get('/', [MaterialApiController::class, 'index']);
    Route::post('/', [MaterialApiController::class, 'store']);
    Route::get('/{id}', [MaterialApiController::class, 'show']);
    Route::put('/{id}', [MaterialApiController::class, 'update']);
    Route::delete('/{id}', [MaterialApiController::class, 'destroy']);
    Route::get('/with-questions/all', [MaterialApiController::class, 'materialsWithQuestions']);
    
    // File access for n8n
    Route::get('/{id}/file-content', [MaterialController::class, 'getFileForN8n']);
    Route::get('/{id}/stream', [MaterialController::class, 'streamFileForN8n']);
    Route::get('/{id}/download', [MaterialController::class, 'apiDownload']);
    
    // Webhook endpoint for n8n automation
    Route::post('/webhook', [MaterialApiController::class, 'webhook']);
});

// Question API Routes
Route::prefix('questions')->middleware(['throttle:60,1'])->group(function () {
    Route::get('/', [QuestionApiController::class, 'index']);
    Route::post('/', [QuestionApiController::class, 'store']);
    Route::get('/{id}', [QuestionApiController::class, 'show']);
    Route::put('/{id}', [QuestionApiController::class, 'update']);
    Route::delete('/{id}', [QuestionApiController::class, 'destroy']);
    
    // Get questions by material
    Route::get('/material/{materialId}', [QuestionApiController::class, 'getByMaterial']);
    
    // Clear completion cache for a material
    Route::delete('/cache/{materialId}', [QuestionApiController::class, 'clearCompletionCache']);
    
    // Webhook endpoint for n8n automation - NEW FORMAT
    Route::post('/webhook', [QuestionApiController::class, 'webhook']);
    
    // Auto-save endpoint for n8n (alternative endpoint name)
    Route::post('/auto-save', [QuestionApiController::class, 'webhook']);
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'Laravel Material & Question API'
    ]);
});

// N8N completion endpoint - handles both POST (from n8n) and GET (from frontend)
// Supports success, error, and progress states
Route::match(['GET', 'POST'], '/n8n/completion/{materialId?}', function (Request $request, $materialId = null) {
    try {
        if ($request->isMethod('POST')) {
            // Handle POST request from n8n (completion, error, or progress update)
            \Log::info('N8N status update received:', $request->all());
            
            $materialId = $request->input('material_id');
            if (!$materialId) {
                return response()->json([
                    'success' => false,
                    'message' => 'material_id is required'
                ], 400);
            }
            
            // Get the material to verify it exists
            $material = \App\Models\Material::find($materialId);
            if (!$material) {
                return response()->json([
                    'success' => false,
                    'message' => 'Material not found'
                ], 404);
            }
            
            $status = $request->input('status', 'completed');
            $questionsCount = $material->questions()->count();
            
            // Store status in cache (completed, error, or progress)
            $cacheKey = "n8n_completion_material_{$materialId}";
            $statusData = [
                'status' => $status,
                'message' => $request->input('message', 'Status updated'),
                'material_id' => $materialId,
                'questions_count' => $questionsCount,
                'updated_at' => now()->toISOString()
            ];
            
            // Add error details if status is error
            if ($status === 'error') {
                $statusData['error_details'] = $request->input('error_details', 'Unknown error occurred');
            }
            
            cache()->put($cacheKey, $statusData, 600); // Cache for 10 minutes
            \Log::info('N8N status cached:', $statusData);
            
            return response()->json([
                'success' => true,
                'message' => 'Status received successfully',
                'data' => $statusData
            ]);
            
        } else {
            // Handle GET request from frontend (check status)
            if (!$materialId) {
                return response()->json([
                    'success' => false,
                    'message' => 'material_id is required in URL'
                ], 400);
            }
            
            $cacheKey = "n8n_completion_material_{$materialId}";
            $statusData = cache()->get($cacheKey);
            
            if ($statusData) {
                $response = [
                    'success' => true,
                    'status' => $statusData['status'],
                    'data' => $statusData
                ];
                
                // Set completed flag based on status
                if ($statusData['status'] === 'completed') {
                    $response['completed'] = true;
                } elseif ($statusData['status'] === 'error') {
                    $response['completed'] = false;
                    $response['error'] = true;
                } else {
                    $response['completed'] = false;
                    $response['progress'] = true;
                }
                
                return response()->json($response);
            } else {
                return response()->json([
                    'success' => true,
                    'completed' => false,
                    'status' => 'processing',
                    'message' => 'N8N process still running...'
                ]);
            }
        }
        
    } catch (\Exception $e) {
        \Log::error('N8N completion error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'status' => 'error',
            'message' => 'Error processing request: ' . $e->getMessage()
        ], 500);
    }
});

