<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Home
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// For changing the current locale
Route::put('locale', function (Request $request) {
    $request->validate(['locale' => 'required|string|min:2']);
    app()->setLocale($request->locale);
    session()->put('locale', $request->locale);
    return redirect()->back();
})->name('locale.update');

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('/time-log', TimeLogController::class)->except('show');
});

require __DIR__ . '/auth.php';
