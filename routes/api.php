<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\UserTaskApiController;
use App\Http\Controllers\Api\TimeLogApiController;
use App\Http\Controllers\Api\TokenApiController;
use Illuminate\Support\Facades\Route;

// Protected api routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('csrf-cookie', fn() => response()->noContent());
    Route::resource('auth/token', TokenApiController::class)->only(['index', 'store']);
    Route::resource('time-log', TimeLogApiController::class)->except(['create', 'edit']);
    Route::resource('user-task', UserTaskApiController::class)->only('index');
});

// Fallback to 404 Not Found should be the last route
Route::fallback([ApiController::class, 'notFound']);
