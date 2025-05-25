<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExerciseSubmissionController;
use App\Http\Controllers\Api\GeminiController as ApiGeminiController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\GeminiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route untuk ambil data user login
Route::middleware('auth:sanctum')->withoutMiddleware(['throttle:api'])->get('/user', function (Request $request) {
    return $request->user();
});

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

// Protected routes tanpa rate limiting throttle:api
Route::middleware('auth:sanctum')->withoutMiddleware(['throttle:api'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'getProfile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);

    // Bahasa & Kursus
    Route::get('/', [LanguageController::class, 'index']);              // GET /languages
    Route::post('/', [LanguageController::class, 'store']);             // POST /languages
    Route::get('/{id}', [LanguageController::class, 'show']);           // GET /languages/{id}
    Route::put('/{id}', [LanguageController::class, 'update']);         // PUT /languages/{id}
    Route::delete('/{id}', [LanguageController::class, 'destroy']);     // DELETE /languages/{id}

    Route::get('/{id}/courses', [LanguageController::class, 'courses']);           // GET /languages/{id}/courses
    Route::get('/courses/{id}/units', [LanguageController::class, 'courseUnits']); // GET /courses/{id}/units
    Route::get('/units/{id}/lessons', [LanguageController::class, 'unitLessons']); // GET /units/{id}/lessons
    Route::get('/lessons/{id}/exercises', [LanguageController::class, 'lessonExercises']); // GET /lessons/{id}/exercises

    Route::post('/exercises/{id}/submit', [ExerciseSubmissionController::class, 'submit']);
    Route::get('/my-submissions', [ExerciseSubmissionController::class, 'userSubmissions']);
    Route::get('/my-submissions/{id}', [ExerciseSubmissionController::class, 'show']);

    Route::prefix('gemini')->name('gemini.')->group(function () {
        Route::post('/generate', [ApiGeminiController::class, 'generateText'])->name('generate');
        Route::post('/chat', [ApiGeminiController::class, 'chat'])->name('chat');
        Route::get('/models', [ApiGeminiController::class, 'models'])->name('models');
        Route::get('/health', [ApiGeminiController::class, 'health'])->name('health');
        // Route::post('/analyze-image', [ApiGeminiController::class, 'analyzeImage'])->name('analyze-image');
    });
});
