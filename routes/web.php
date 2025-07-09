<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\MaterialController;

Route::get('/', function () {
    return view('welcome');
});

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


// Form Manual Input
Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

// Manage and Edit Questions 
Route::get('/questions/manage', [QuestionController::class, 'manage'])->name('questions.manage');
Route::get('/questions/{id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('questions.update');
Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');
