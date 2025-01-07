<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\UserTaskApiController;
use App\Http\Controllers\Api\TimeLogApiController;
use App\Http\Controllers\Api\TokenApiController;
use Illuminate\Support\Facades\Route;

// Protected api routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('csrf-cookie', fn() => response()->noContent());
    Route::resource('auth/token', TokenApiController::class, ['as' => 'api'])->only(['index', 'store']);
    Route::resource('time-log', TimeLogApiController::class, ['as' => 'api'])->except(['create', 'edit']);
    Route::resource('user-task', UserTaskApiController::class, ['as' => 'api'])->only('index');
});

// Fallback to 404 Not Found should be the last route
Route::fallback([ApiController::class, 'notFound']);
