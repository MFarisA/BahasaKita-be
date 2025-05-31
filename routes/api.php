<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExerciseSubmissionController;
use App\Http\Controllers\Api\GetDataController;
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

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/auth/google/url', [GoogleController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/GetAllLanguage', [LanguageController::class, 'index']);
Route::get('/Language/{id}', [LanguageController::class, 'show']);


Route::middleware('auth:sanctum')->withoutMiddleware(['throttle:api'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/getProfile', [UserController::class, 'getProfile']);
    Route::put('/profile', [UserController::class, 'updateProfile']);

    Route::get('/exercises/{languageId}/{courseId}/{unitId}/{subUnitId}', [GetDataController::class, 'getExercises']);
    Route::get('/subunits/{languageId}/{courseId}/{unitId}', [GetDataController::class, 'getSubUnit']);
    Route::get('/units/{languageId}/{courseId}', [GetDataController::class, 'getUnit']);
    Route::get('/courses/{languageId}', [GetDataController::class, 'getCourse']);

    Route::post('/exercise/submit/{exercise_id}', [ExerciseSubmissionController::class, 'submit']);
    Route::get('/languages/{languageId}/courses/{courseId}/units/{unitId}/sub-units/{subUnitId}/exercises',[ExerciseSubmissionController::class, 'showExercises']);
    Route::get(
        '/languages/{languageId}/courses/{courseId}/units/{unitId}',
        [ExerciseSubmissionController::class, 'showUnit']
    );


    // Route::get('/notifications', [NotificationController::class, 'index']);
    // Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);

    // Route::post('/notifications/reminders', [NotificationController::class, 'sendStudyReminders']);
    // Route::post('/notifications/new-lesson/{lesson}', [NotificationController::class, 'notifyNewLesson']);
    // Route::post('/notifications/milestones', function () {
    //     foreach (\App\Models\User::all() as $user) {
    //         app(NotificationController::class)->checkMilestones($user);
    //     }
    // });

    Route::get('/leaderboard/global', [LeaderBoardGameController::class, 'globalLeaderboard']);
    Route::get('/leaderboard/weekly', [LeaderBoardGameController::class, 'weeklyLeaderboard']);
});
