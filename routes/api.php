<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExerciseController;
use App\Http\Controllers\Api\GeminiController as ApiGeminiController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\LeaderBoardGameController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route untuk ambil data user login
Route::middleware('auth:sanctum')->withoutMiddleware(['throttle:api'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/exercises', [ExerciseController::class, 'index']);
// Public routes
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/register', [AuthController::class, 'register']);
Route::get('/auth/google/url', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/GetAllLanguage', [LanguageController::class, 'index']);              // GET /languages
Route::get('/Language/{id}', [LanguageController::class, 'show']);           // GET /languages/{id}

// Protected routes tanpa rate limiting throttle:api
Route::middleware('auth:sanctum')->withoutMiddleware(['throttle:api'])->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'getProfile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);


    Route::get('/exercises', [ExerciseController::class, 'index']);
    Route::get('/courses/{courseId}/units/{unitId}/exercises', [ExerciseController::class, 'getExercisesByUnitAndCourse']);

    

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Trigger-only endpoints (bisa dijadwalkan lewat scheduler)
    Route::post('/notifications/reminders', [NotificationController::class, 'sendStudyReminders']);
    Route::post('/notifications/new-lesson/{lesson}', [NotificationController::class, 'notifyNewLesson']);
    Route::post('/notifications/milestones', function () {
        foreach (\App\Models\User::all() as $user) {
            app(NotificationController::class)->checkMilestones($user);
        }
    });

    Route::get('/leaderboard/global', [LeaderBoardGameController::class, 'globalLeaderboard']);
    Route::get('/leaderboard/weekly', [LeaderBoardGameController::class, 'weeklyLeaderboard']);
    Route::get('/leaderboard/me', [LeaderBoardGameController::class, 'myProgress']);

    // Route::prefix('gemini')->name('gemini.')->group(function () {
    //     Route::post('/generate', [ApiGeminiController::class, 'generateText'])->name('generate');
    //     Route::post('/chat', [ApiGeminiController::class, 'chat'])->name('chat');
    //     Route::get('/models', [ApiGeminiController::class, 'models'])->name('models');
    //     Route::get('/health', [ApiGeminiController::class, 'health'])->name('health');
    //     Route::post('/analyze-image', [ApiGeminiController::class, 'analyzeImage'])->name('analyze-image');
    // });
});
