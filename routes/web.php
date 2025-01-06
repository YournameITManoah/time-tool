<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;

// Home
Route::get('/', [WelcomeController::class, 'index'])->name('home');

// Protected routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('/time-log', TimeLogController::class)->except('show');
});

require __DIR__ . '/auth.php';
