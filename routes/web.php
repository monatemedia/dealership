<?php // routes/web.php

use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\VehicleCategoryController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleTypeController;
use App\Http\Controllers\WatchlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home route
Route::get('/', [HomeController::class, 'index'])->name('home');

// -------------------------------
// CATEGORY HIERARCHY ROUTES
// -------------------------------

// 1️⃣ MAIN CATEGORIES
Route::get('/main-categories', [MainCategoryController::class, 'index'])
    ->name('main-categories.index');

Route::get('/{mainCategory:slug}', [MainCategoryController::class, 'show'])
    ->name('main-categories.show');

// 2️⃣ SUBCATEGORIES (nested under main category)
Route::get('/{mainCategory:slug}/{subCategory:slug}', [SubCategoryController::class, 'show'])
    ->name('sub-categories.show');

//  Listing all subcategories
Route::get('/sub-categories', [SubCategoryController::class, 'index'])
    ->name('sub-categories.index');

// 3️⃣ VEHICLE TYPES (nested under subcategory)
Route::get('/{mainCategory:slug}/{subCategory:slug}/{vehicleType:slug}', [VehicleTypeController::class, 'show'])
    ->name('vehicle-types.show');

// List all vehicle types for a subcategory
Route::get('/{mainCategory:slug}/{subCategory:slug}/vehicle-types', [VehicleTypeController::class, 'index'])
    ->name('vehicle-types.index');

Route::get('/{mainCategory:slug}/{subCategory:slug}/{vehicleType:slug}', [VehicleTypeController::class, 'show'])
    ->name('vehicle-types.show');

// -------------------------------
// VEHICLE ROUTES
// -------------------------------

Route::get('/vehicle/search', [VehicleController::class, 'search'])
    ->name('vehicle.search');

Route::get('/vehicle/{vehicle}', [VehicleController::class, 'show'])
    ->name('vehicle.show');

Route::post('/vehicle/phone/{vehicle}', [VehicleController::class, 'showPhone'])
    ->name('vehicle.showPhone');

// -------------------------------
// AUTHENTICATED ROUTES
// -------------------------------

Route::middleware(['auth', 'verified'])->group(function () {

    // Vehicle management
    Route::resource('vehicle', VehicleController::class)->except(['show']);

    // Multi-step vehicle creation flow
    Route::get('/vehicle/create/main-categories', [VehicleCategoryController::class, 'indexMainCategories'])
        ->name('vehicle.main-categories');

    Route::get('/vehicle/create/sub-categories/{mainCategory:slug}', [VehicleCategoryController::class, 'indexSubCategories'])
        ->name('vehicle.sub-categories');

    // Vehicle images management
    Route::get('/vehicle/{vehicle}/images', [VehicleController::class, 'vehicleImages'])->name('vehicle.images');
    Route::put('/vehicle/{vehicle}/images', [VehicleController::class, 'updateImages'])->name('vehicle.updateImages');
    Route::post('/vehicle/{vehicle}/images', [VehicleController::class, 'addImages'])->name('vehicle.addImages');
    Route::post('/vehicle/{vehicle}/images/sync', [VehicleController::class, 'syncImages'])->name('vehicle.syncImages');

    // API route
    Route::get('/api/vehicle-image/status', [VehicleController::class, 'status'])
        ->name('api.vehicle-image.status');

    // Watchlist
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/{vehicle}', [WatchlistController::class, 'storeDestroy'])->name('watchlist.storeDestroy');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Include authentication routes
require __DIR__ . '/auth.php';
