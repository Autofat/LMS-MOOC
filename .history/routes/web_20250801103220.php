<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AuthController;

// Root redirect to login
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('materials.index');
    }
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Temporary route to clear session
Route::get('/clear-session', function () {
    Auth::logout();
    session()->flush();
    session()->regenerate();
    return redirect()->route('login')->with('success', 'Session cleared. Please login again.');
});

// Protected Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    
    // Debug route for testing n8n connection
    Route::get('/test-n8n-connection', function () {
        try {
            $testData = [
                'action' => 'test_connection',
                'timestamp' => now()->toISOString(),
                'message' => 'Laravel connection test'
            ];
            
            $n8nUrl = env('N8N_GENERATE_QUESTIONS_URL', 'http://localhost:5678/webhook/generate-questions');
            
            $response = Http::timeout(120)
                ->retry(2, 100)
                ->withOptions([
                    'verify' => false,
                    'allow_redirects' => true,
                    'http_errors' => false
                ])
                ->post($n8nUrl, $testData);
            
            return response()->json([
                'success' => true,
                'message' => 'n8n connection test completed',
                'url' => $n8nUrl,
                'request_data' => $testData,
                'response' => [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'body' => $response->body(),
                    'successful' => $response->successful()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'error_class' => get_class($e)
            ], 500);
        }
    })->name('test.n8n.connection');

    // Material Routes
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{id}', [MaterialController::class, 'show'])->name('materials.show');
    Route::get('/materials/{id}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/materials/{id}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{id}', [MaterialController::class, 'destroy'])->name('materials.destroy');
    Route::get('/materials/{id}/download', [MaterialController::class, 'download'])->name('materials.download');
    Route::get('/materials/{id}/questions', [MaterialController::class, 'questions'])->name('materials.questions');

    // Generate Questions with n8n
    Route::post('/materials/{id}/generate-questions', [MaterialController::class, 'generateQuestions'])->name('materials.generate-questions');
    Route::post('/materials/{id}/generate-questions-async', [MaterialController::class, 'generateQuestionsAsync'])->name('materials.generate-questions-async');
    Route::get('/materials/{id}/test-n8n', [MaterialController::class, 'testN8nConnection'])->name('materials.test-n8n');

    // Form Manual Input
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

    // Manage and Edit Questions 
    Route::get('/questions/manage', [QuestionController::class, 'manage'])->name('questions.manage');
    Route::get('/questions/{id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');
});
