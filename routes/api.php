<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ExerciseSubmissionController;
use App\Http\Controllers\Api\GetDataController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\LeaderBoardController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CulturalContentController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->withoutMiddleware(['throttle:api'])->get('/user', function (Request $request) {
    return $request->user();
});

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

    Route::get('/leaderboard/global', [LeaderBoardController::class, 'getLeaderboard']);

    Route::post('/exercise/submit/{exercise_id}', [ExerciseSubmissionController::class, 'submit']);
    Route::get('/languages/{languageId}/courses/{courseId}/units/{unitId}/sub-units/{subUnitId}/exercises', [ExerciseSubmissionController::class, 'showExercises']);
    Route::get('/languages/{languageId}/courses/{courseId}/units/{unitId}', [ExerciseSubmissionController::class, 'showUnit']);

    Route::prefix('cultural-content')->group(function () {
        Route::get('/', [CulturalContentController::class, 'index']);
        Route::get('/{type}', [CulturalContentController::class, 'getByType'])
            ->where('type', 'story|proverb|trivia');
    });
});
