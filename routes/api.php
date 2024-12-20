<?php

use App\Http\Controllers\Api\UserTaskApiController;
use App\Http\Controllers\Api\TimeLogApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


// All api routes start with /api/
Route::prefix('api')->group(function () {

    Route::put('locale', function (Request $request) {
        $request->validate(['locale' => 'required|string|min:2']);
        app()->setLocale($request->locale);
        session()->put('locale', $request->locale);
        return redirect()->back();
    })->name('locale.update');

    // Protected api routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/time-log/my', [TimeLogApiController::class, 'index']);
        Route::get('/user-task/my', [UserTaskApiController::class, 'index']);
    });

    Route::fallback(function () {
        return response()->json(['message' => 'Not Found.'], 404);
    });
});
