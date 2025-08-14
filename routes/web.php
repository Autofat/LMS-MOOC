<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HelpController;

// Root redirect to login
Route::get('/', function () {
    // Force logout and clear session
    Auth::logout();
    session()->flush();
    session()->regenerate();
    
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
// Register routes disabled - Admin only access
// Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
// Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Temporary route to clear session
Route::get('/clear-session', function () {
    Auth::logout();
    session()->flush();
    session()->regenerate();
    return redirect()->route('login')->with('success', 'Session cleared. Please login again.');
});

// User Routes (for regular users - read only access)
Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [MaterialController::class, 'index'])->name('user.dashboard');
    Route::get('/user/materials', [MaterialController::class, 'index'])->name('user.materials.index');
    Route::get('/user/materials/{id}', [MaterialController::class, 'show'])->name('user.materials.show');
});

// Protected Routes (require authentication - accessible by both admin and regular users)
Route::middleware(['auth'])->group(function () {
    // Material Routes
    Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
    Route::get('/materials/create', [MaterialController::class, 'create'])->name('materials.create');
    Route::post('/materials', [MaterialController::class, 'store'])->name('materials.store');
    Route::get('/materials/{id}', [MaterialController::class, 'show'])->name('materials.show');
    Route::get('/materials/{id}/edit', [MaterialController::class, 'edit'])->name('materials.edit');
    Route::put('/materials/{id}', [MaterialController::class, 'update'])->name('materials.update');
    Route::delete('/materials/{id}', [MaterialController::class, 'destroy'])->name('materials.destroy');
    Route::get('/materials/{id}/download', [MaterialController::class, 'download'])->name('materials.download');
    Route::get('/materials/{id}/download-questions-excel', [MaterialController::class, 'downloadQuestionsExcel'])->name('materials.download.questions.excel');
    Route::get('/materials/category/{category}/download-questions-excel', [MaterialController::class, 'downloadCategoryQuestionsExcel'])->name('materials.download.category.excel');
    Route::get('/materials/category/{category}/detail', [MaterialController::class, 'categoryDetail'])->name('materials.category.detail');
    Route::delete('/materials/category/{category}', [MaterialController::class, 'destroyCategory'])->name('materials.category.destroy');
    Route::delete('/categories/{id}', [MaterialController::class, 'destroyCategoryById'])->name('categories.destroy');
    Route::get('/categories/{id}/edit', [MaterialController::class, 'editCategory'])->name('categories.edit');
    Route::put('/categories/{id}', [MaterialController::class, 'updateCategory'])->name('categories.update');
    Route::post('/materials/categories', [MaterialController::class, 'storeCategory'])->name('materials.categories.store');
    Route::get('/materials/{id}/questions', [MaterialController::class, 'questions'])->name('materials.questions');

    // Sub Category Routes
    Route::post('/materials/sub-categories', [MaterialController::class, 'storeSubCategory'])->name('materials.sub-categories.store');
    Route::delete('/sub-categories/{id}', [MaterialController::class, 'destroySubCategory'])->name('sub-categories.destroy');

    // Generate Questions with n8n
    Route::post('/materials/{id}/generate-questions', [MaterialController::class, 'generateQuestions'])->name('materials.generate-questions');
    Route::post('/materials/{id}/generate-questions-async', [MaterialController::class, 'generateQuestionsAsync'])->name('materials.generate-questions-async');
    Route::post('/materials/{id}/clear-generation-state', [MaterialController::class, 'clearGenerationState'])->name('materials.clear-generation-state');

    // Form Manual Input
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');

    // Manage and Edit Questions 
    Route::get('/questions/manage', [QuestionController::class, 'manage'])->name('questions.manage');
    Route::get('/questions/{id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Help Routes
    Route::get('/help', [HelpController::class, 'index'])->name('help.index');
    Route::get('/help/contextual/{page}', [HelpController::class, 'contextualHelp'])->name('help.contextual');
});

// Admin Only Routes (user management - only accessible by admin)
Route::middleware(['auth', 'superadmin'])->group(function () {
    // Admin Management Routes
    Route::get('/admin/create', [AuthController::class, 'showCreateAdminForm'])->name('admin.create');
    Route::post('/admin/create', [AuthController::class, 'createAdmin'])->name('admin.store');
    Route::get('/admin/manage', [AuthController::class, 'manageAdmins'])->name('admin.manage');
    Route::delete('/admin/{id}', [AuthController::class, 'deleteAdmin'])->name('admin.delete');
});
