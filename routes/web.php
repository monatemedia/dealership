<?php // routes/web.php

use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\SubCategoryController;
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
| Routes are organized from MOST SPECIFIC to LEAST SPECIFIC
| This prevents slug-based routes from catching everything
*/

// -------------------------------
// HOME
// -------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');

// -------------------------------
// AUTHENTICATED ROUTES (Specific paths first)
// -------------------------------
Route::middleware(['auth', 'verified'])->group(function () {

    // Vehicle management
    Route::resource('vehicle', VehicleController::class)->except(['show']);

    // Vehicle management - specific paths
    Route::get('/vehicle/create', [VehicleController::class, 'create'])->name('vehicle.create');
    Route::post('/vehicle', [VehicleController::class, 'store'])->name('vehicle.store');
    Route::get('/vehicle/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicle.edit');
    Route::put('/vehicle/{vehicle}', [VehicleController::class, 'update'])->name('vehicle.update');
    Route::delete('/vehicle/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicle.destroy');

    // Multi-step vehicle creation flow
    Route::get('/vehicle/create/main-categories', [MainCategoryController::class, 'indexMainCategories'])
        ->name('vehicle.main-categories');
    Route::get('/vehicle/create/sub-categories/{mainCategory:slug}', [SubCategoryController::class, 'indexSubCategories'])
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

// -------------------------------
// AUTHENTICATION ROUTES (Include before slug routes)
// -------------------------------
require __DIR__ . '/auth.php';

// -------------------------------
// PUBLIC SPECIFIC ROUTES
// -------------------------------

// Vehicle search (must come before vehicle show)
Route::get('/vehicle/search', [VehicleController::class, 'search'])
    ->name('vehicle.search');

// Vehicle show with phone
Route::post('/vehicle/phone/{vehicle}', [VehicleController::class, 'showPhone'])
    ->name('vehicle.showPhone');

// Vehicle show (specific path)
Route::get('/vehicle/{vehicle}', [VehicleController::class, 'show'])
    ->name('vehicle.show');

// Category listing pages (specific paths)
Route::get('/main-categories', [MainCategoryController::class, 'index'])
    ->name('main-categories.index');

Route::get('/sub-categories', [SubCategoryController::class, 'index'])
    ->name('sub-categories.index');

// -------------------------------
// CATEGORY HIERARCHY ROUTES (Slug-based - MUST BE LAST)
// -------------------------------

// 3️⃣ VEHICLE TYPES (most specific slug route - 3 segments)
Route::get('/{mainCategory:slug}/{subCategory:slug}/vehicle-types', [VehicleTypeController::class, 'index'])
    ->name('vehicle-types.index');

Route::get('/{mainCategory:slug}/{subCategory:slug}/{vehicleType:slug}', [VehicleTypeController::class, 'show'])
    ->name('vehicle-types.show');

// 2️⃣ SUBCATEGORIES (2 segments)
Route::get('/{mainCategory:slug}/{subCategory:slug}', [SubCategoryController::class, 'show'])
    ->name('sub-categories.show');

// 1️⃣ MAIN CATEGORIES (least specific - single segment, MUST BE LAST!)
Route::get('/{mainCategory:slug}', [MainCategoryController::class, 'show'])
    ->name('main-categories.show');
