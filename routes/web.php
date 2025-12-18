<?php // routes/web.php

use App\Http\Controllers\MainCategoryController;
use App\Http\Controllers\SubcategoryController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VehicleSearchController;
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
// AWS SES EVENTS WEBHOOK (No CSRF)
// -------------------------------
Route::post('/api/aws/ses-events',
    [\App\Http\Controllers\AwsSnsWebhookController::class,
    'handle'])->withoutMiddleware(
        [\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]
    )->name('aws.ses-events.handle');

// -------------------------------
// HOME
// -------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');

// -------------------------------
// AUTHENTICATED ROUTES (Specific paths first)
// -------------------------------
Route::middleware(['auth', 'verified'])->group(function () {
    // Vehicle management
    // Define only 'index' and 'create' via resource, preventing name conflicts below.
    Route::resource('vehicle', VehicleController::class)->only(['index', 'create']);

    // Vehicle management - specific paths (Manually defined to avoid conflicts)
    Route::post('/vehicle', [VehicleController::class, 'store'])->name('vehicle.store');
    Route::get('/vehicle/{vehicle}/edit', [VehicleController::class, 'edit'])->name('vehicle.edit');
    Route::put('/vehicle/{vehicle}', [VehicleController::class, 'update'])->name('vehicle.update');
    Route::delete('/vehicle/{vehicle}', [VehicleController::class, 'destroy'])->name('vehicle.destroy');

    // Multi-step vehicle creation flow
    Route::get('/vehicle/create/main-categories', [MainCategoryController::class, 'indexMainCategories'])
        ->name('vehicle.main-categories');
    Route::get('/vehicle/create/sub-categories/{mainCategory:slug}', [SubcategoryController::class, 'indexSubcategories'])
        ->name('vehicle.sub-categories');

    // Vehicle images management
    Route::get('/vehicle/{vehicle}/images', [VehicleController::class, 'vehicleImages'])->name('vehicle.images');
    Route::put('/vehicle/{vehicle}/images', [VehicleController::class, 'updateImages'])->name('vehicle.updateImages');
    Route::post('/vehicle/{vehicle}/images', [VehicleController::class, 'addImages'])->name('vehicle.addImages');
    Route::post('/vehicle/{vehicle}/images/sync', [VehicleController::class, 'syncImages'])->name('vehicle.syncImages');

    // API route
    Route::get('/api/vehicle-image/status', [VehicleController::class, 'status'])
        ->name('api.vehicle-image.status');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});

// -------------------------------
// AUTHENTICATED-ONLY ROUTES (Only needs Auth, NOT Verification)
// -------------------------------
Route::middleware(['auth'])->group(function () {
    // Logout should ONLY be protected by 'auth'
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Watchlist - must be authenticated
    Route::get('/watchlist', [WatchlistController::class, 'index'])->name('watchlist.index');
    Route::post('/watchlist/{vehicle}', [WatchlistController::class, 'storeDestroy'])->name('watchlist.storeDestroy');
});

// -------------------------------
// AUTHENTICATION ROUTES (Include before slug routes)
// -------------------------------
require __DIR__ . '/auth.php';

// -------------------------------
// API ROUTES REQUIRING SESSIONS (Specific paths first)
// -------------------------------
// InstantSearch API endpoint
Route::get('/api/vehicles/search', [VehicleSearchController::class, 'instantSearch'])
    ->name('vehicle.instantSearch');

// -------------------------------
// PUBLIC SPECIFIC ROUTES
// -------------------------------
// Main search page
Route::get('/vehicles/search', [VehicleSearchController::class, 'index'])
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

// Legal pages
Route::view('/terms', 'legal.terms')->name('terms');
Route::view('/privacy-policy', 'legal.privacy')->name('privacy');

// Other Pages
Route::view('/contact', 'pages.contact')->name('contact');


// -------------------------------
// CATEGORY HIERARCHY ROUTES (Slug-based - MUST BE LAST)
// -------------------------------
// 3️⃣ VEHICLE TYPES (most specific slug route - 3 segments)
Route::get('/{mainCategory}/{subcategory}/vehicle-types', [VehicleTypeController::class, 'index'])
    ->name('vehicle-types.index');
Route::get('/{mainCategory}/{subcategory}/{vehicleType}', [VehicleTypeController::class, 'show'])
    ->name('vehicle-types.show');

// 2️⃣ SUBCATEGORIES (2 segments)
// This will handle URLs like /light-vehicles/sub-categories
Route::get('/{mainCategory}/sub-categories', [SubcategoryController::class, 'index'])
    ->name('main-category.sub-categories.index');
Route::get('/{mainCategory}/{subcategory}', [SubcategoryController::class, 'show'])
    ->name('sub-categories.show');

// 1️⃣ MAIN CATEGORIES (least specific - single segment, MUST BE LAST!)
Route::get('/{mainCategory}', [MainCategoryController::class, 'show'])
    ->name('main-categories.show');
