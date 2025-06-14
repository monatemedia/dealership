<?php

use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Car Search Routes
Route::get('/car/search', [CarController::class, 'search'])
    ->name('car.search');

// Routes for authenticated users
Route::middleware(['auth'])->group(function () {

    // Verified user routes
    Route::middleware(['verified'])->group(function () {
        Route::get('/car/watchlist', [CarController::class, 'watchlist'])
            ->name('car.watchlist');
        Route::resource('car', CarController::class)->except(['show']);
        Route::get('/car/{car}/images', [CarController::class, 'carImages'])
            ->name('car.images');
        Route::put('/car/{car}/images', [CarController::class, 'updateImages'])
            ->name('car.updateImages');
        Route::post('/car/{car}/images', [CarController::class, 'addImages'])
            ->name('car.addImages');
    });

    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.updatePassword');
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});

// Public car details route
Route::get('/car/{car}', [CarController::class, 'show'])->name('car.show');

// Include the authentication routes
require_once __DIR__ . '/auth.php';
