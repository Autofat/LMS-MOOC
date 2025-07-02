<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;

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
