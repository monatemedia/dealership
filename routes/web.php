<?php // routes/web.php

use App\Http\Controllers\VehicleCategoryController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WatchlistController;
use App\Http\Controllers\VehicleImageController;
use App\Models\VehicleCategory;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Get all category slugs for route constraint
$slugs = VehicleCategory::pluck('slug')->implode('|');

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// Vehicle Search Routes
Route::get('/vehicle/search', [VehicleController::class, 'search'])
    ->name('vehicle.search');

// Categories
Route::get('/categories', [VehicleCategoryController::class, 'index'])
    ->name('categories.index');

// Routes for authenticated users
Route::middleware(['auth'])->group(function () {

    // Verified user routes
    Route::middleware(['verified'])->group(function () {

        // Vehicle management
        Route::resource('vehicle', VehicleController::class)->except(['show']);

        // Vehicle images management
        Route::get('/vehicle/{vehicle}/images', [VehicleController::class, 'vehicleImages'])
            ->name('vehicle.images');
        Route::put('/vehicle/{vehicle}/images', [VehicleController::class, 'updateImages'])
            ->name('vehicle.updateImages');
        Route::post('/vehicle/{vehicle}/images', [VehicleController::class, 'addImages'])
            ->name('vehicle.addImages');
        Route::post('/vehicle/{vehicle}/images/sync', [VehicleController::class, 'syncImages'])
            ->name('vehicle.syncImages');
        Route::get('/api/vehicle-image/status', [VehicleController::class, 'status'])
            ->name('api.vehicle-image.status');
    });

    // Watchlist routes
    Route::get('/watchlist', [WatchlistController::class, 'index'])
        ->name('watchlist.index');
    Route::post('/watchlist/{vehicle}', [WatchlistController::class, 'storeDestroy'])
        ->name('watchlist.storeDestroy');

    // Profile management routes
    Route::get('/profile', [ProfileController::class, 'index'])
        ->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.updatePassword');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});

// Public vehicle details route (AFTER authenticated routes)
Route::get('/vehicle/{vehicle}', [VehicleController::class, 'show'])
    ->name('vehicle.show');
Route::post('/vehicle/phone/{vehicle}', [VehicleController::class, 'showPhone'])
    ->name('vehicle.showPhone');

// Category route (keep at the very end)
Route::get('/{category:slug}', [VehicleCategoryController::class, 'show'])
    ->where('category', $slugs) // restrict to valid slugs only
    ->name('category.show');

// Include the authentication routes
require __DIR__ . '/auth.php';
