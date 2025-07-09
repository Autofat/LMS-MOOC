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
    
    // Bulk create questions
    Route::post('/bulk', [QuestionApiController::class, 'bulkStore']);
    Route::post('/array', [QuestionApiController::class, 'storeFromArray']);
    
    // Webhook endpoint for n8n automation
    Route::post('/webhook', [QuestionApiController::class, 'webhook']);
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
        'service' => 'Laravel Material & Question API'
    ]);
});

// Endpoint utama untuk n8n - robust handling (RECOMMENDED)
Route::any('/questions/n8n', [QuestionController::class, 'n8nEndpoint'])->name('api.questions.n8n');

// Endpoint alternatif untuk n8n (backward compatibility)
Route::any('/questions/auto-save', [QuestionController::class, 'autoSaveFromN8n'])->name('api.questions.auto.save');

// Endpoint untuk testing dan debugging
Route::any('/ping', [QuestionController::class, 'ping'])->name('api.ping');
Route::any('/echo', [QuestionController::class, 'echo'])->name('api.echo');
Route::any('/questions/debug', [QuestionController::class, 'testEndpoint'])->name('api.questions.debug');

// API untuk mendapatkan semua questions (JSON output)
Route::get('/questions', [QuestionController::class, 'index'])->name('api.questions.index');
