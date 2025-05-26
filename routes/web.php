<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;

// Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
// Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::get('/', function () {
    return view('welcome');
});
