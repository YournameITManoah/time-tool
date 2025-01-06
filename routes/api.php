<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\UserTaskApiController;
use App\Http\Controllers\Api\TimeLogApiController;
use App\Http\Controllers\Api\TokenApiController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


// For changing the current locale
Route::put('locale', function (Request $request) {
    $request->validate(['locale' => 'required|string|min:2']);
    app()->setLocale($request->locale);
    session()->put('locale', $request->locale);
    return redirect()->back();
})->name('locale.update');

// Protected api routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('token', TokenApiController::class)->only(['index', 'store']);
    Route::resource('time-log', TimeLogApiController::class)->except(['create', 'edit']);
    Route::resource('user-task', UserTaskApiController::class)->only('index');
});

// Fallback to 404 Not Found should be the last route
Route::fallback([ApiController::class, 'notFound']);
