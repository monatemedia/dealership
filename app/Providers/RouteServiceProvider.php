<?php // app/Providers/RouteServiceProvider.php

namespace App\Providers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\VehicleType;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public const HOME = '/';

    public function boot(): void
    {
        // Prevents abuse by limiting how many requests users can make:
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });

        // -------------------------------
        // Scoped Route Bindings
        // -------------------------------

        /**
         * SubCategory Binding
         * Bind SubCategory under MainCategory
         * When Laravel sees `{mainCategory}/{subCategory}` in a route:
         * - Finds the main category by slug (`transport`)
         * - Then finds the subcategory **only within that main category** (`motorcycles`)
         * - Returns 404 if the subcategory doesn't belong to that main category
         */
        Route::bind('subCategory', function ($value, $route) {
            $mainCategorySlug = $route->parameter('mainCategory');
            $mainCategory = MainCategory::where('slug', $mainCategorySlug)->firstOrFail();
            return SubCategory::where('slug', $value)
                ->where('main_category_id', $mainCategory->id)
                ->firstOrFail();
        });

        /**
         * VehicleType Binding
         * Bind VehicleType under SubCategory
         * When Laravel sees `{subCategory}/{vehicleType}` in a route:
         * - Finds the subcategory by slug (`motorcycles`)
         * - Then finds the vehicle type **only within that subcategory** (`sport-bikes`)
         * - Returns 404 if the vehicle type doesn't belong to that subcategory
         */
        Route::bind('vehicleType', function ($value, $route) {
            $subCategorySlug = $route->parameter('subCategory');
            $subCategory = SubCategory::where('slug', $subCategorySlug)->firstOrFail();
            return VehicleType::where('slug', $value)
                ->where('sub_category_id', $subCategory->id)
                ->firstOrFail();
        });
    }


    // -------------------------------
    // Configure Rate Limiting
    // -------------------------------
    protected function configureRateLimiting(): void
    {
        // API routes: 60 requests per minute (tracked by user ID or IP address)
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Web routes: 120 requests per minute (tracked by IP address)
        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });
    }
}
