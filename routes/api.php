<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\GeminiController as ApiGeminiController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route untuk ambil data user login
Route::middleware('auth:sanctum')->withoutMiddleware(['throttle:api'])->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/auth/google/url', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/', [LanguageController::class, 'index']);              // GET /languages
Route::get('/{id}', [LanguageController::class, 'show']);           // GET /languages/{id}

// Protected routes tanpa rate limiting throttle:api
Route::middleware('auth:sanctum')->withoutMiddleware(['throttle:api'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'getProfile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);

    Route::get('/{id}/courses', [ExerciseController::class, 'courses']);           // GET /languages/{id}/courses
    Route::get('/courses/{id}/units', [ExerciseController::class, 'courseUnits']); // GET /courses/{id}/units
    Route::get('/units/{id}/lessons', [ExerciseController::class, 'unitLessons']); // GET /units/{id}/lessons
    Route::get('/lessons/{id}/exercises', [ExerciseController::class, 'lessonExercises']); // GET /lessons/{id}/exercises

    Route::post('/exercises/{id}/submit', [ExerciseController::class, 'submit']);
    Route::get('/my-submissions', [ExerciseController::class, 'userSubmissions']);
    Route::get('/my-submissions/{id}', [ExerciseController::class, 'show']);

    Route::prefix('gemini')->name('gemini.')->group(function () {
        Route::post('/generate', [ApiGeminiController::class, 'generateText'])->name('generate');
        Route::post('/chat', [ApiGeminiController::class, 'chat'])->name('chat');
        Route::get('/models', [ApiGeminiController::class, 'models'])->name('models');
        Route::get('/health', [ApiGeminiController::class, 'health'])->name('health');
        // Route::post('/analyze-image', [ApiGeminiController::class, 'analyzeImage'])->name('analyze-image');
    });
});
