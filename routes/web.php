<?php

// routes/web.php

use App\Http\Controllers\CarController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WatchlistController;
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
        // Page that shows the images UI
        Route::resource('car', CarController::class)->except(['show']);
        Route::get('/car/{car}/images', [CarController::class, 'carImages'])
            ->name('car.images');
        Route::put('/car/{car}/images', [CarController::class, 'updateImages'])
            ->name('car.updateImages');
        Route::post('/car/{car}/images', [CarController::class, 'addImages'])
            ->name('car.addImages');
        // Single endpoint that handles deletions + reordering + new uploads
        Route::post('/car/{car}/images/sync', [CarController::class, 'syncImages'])
            ->name('car.syncImages');
        // API to fetch status of Cars Images
        Route::get('/api/car-image/status', [CarController::class, 'status'])
            ->name('api.car-image.status');
    });

    // Watchlist routes
    Route::get('/watchlist', [WatchlistController::class, 'index'])
        ->name('watchlist.index');
    Route::post('/watchlist/{car}', [WatchlistController::class, 'storeDestroy'])
        ->name('watchlist.storeDestroy');

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
Route::post('/car/phone/{car}', [CarController::class, 'showPhone'])->name('car.showPhone');

// Include the authentication routes
require __DIR__ . '/auth.php';
