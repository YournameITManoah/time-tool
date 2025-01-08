<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\UserTaskApiController;
use App\Http\Controllers\Api\CsrfCookieApiController;
use App\Http\Controllers\Api\TimeLogApiController;
use App\Http\Controllers\Api\AuthTokenApiController;
use Illuminate\Support\Facades\Route;

// Start secure session
Route::get('csrf-cookie', [CsrfCookieApiController::class, 'index']);

// Get personal access token
Route::post('auth/token', [AuthTokenApiController::class, 'store']);

// Protected api routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('time-log', TimeLogApiController::class, ['as' => 'api'])->except(['create', 'edit']);
    Route::resource('user-task', UserTaskApiController::class, ['as' => 'api'])->only('index');
});

// Fallback to 404 Not Found should be the last route
Route::fallback([ApiController::class, 'notFound']);
