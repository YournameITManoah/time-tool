<?php

use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\Api\TimeLogApiController;
use Illuminate\Support\Facades\Route;


// All api routes start with /api/
Route::prefix('api')->group(function () {

    // Protected api routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/time-log/my', [TimeLogApiController::class, 'index']);
        Route::get('/project/my', [ProjectApiController::class, 'index']);
    });

    Route::fallback(function () {
        return response()->json(['message' => 'Not Found.'], 404);
    });
});
