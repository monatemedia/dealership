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
         * MainCategory Binding
         * Resolve MainCategory by slug first
         */
        Route::bind('mainCategory', function ($value) {
            return MainCategory::where('slug', $value)->firstOrFail();
        });

        /**
         * SubCategory Binding
         * Bind SubCategory under MainCategory
         * The mainCategory parameter is now already resolved to a model
         */
        Route::bind('subCategory', function ($value, $route) {
            // Get the already-resolved MainCategory model
            $mainCategory = $route->parameter('mainCategory');

            // If mainCategory is still a string (shouldn't happen but safety check)
            if (is_string($mainCategory)) {
                $mainCategory = MainCategory::where('slug', $mainCategory)->firstOrFail();
            }

            return SubCategory::where('slug', $value)
                ->where('main_category_id', $mainCategory->id)
                ->firstOrFail();
        });

        /**
         * VehicleType Binding
         * Bind VehicleType under SubCategory
         * The subCategory parameter is now already resolved to a model
         */
        Route::bind('vehicleType', function ($value, $route) {
            // Get the already-resolved SubCategory model
            $subCategory = $route->parameter('subCategory');

            // If subCategory is still a string (shouldn't happen but safety check)
            if (is_string($subCategory)) {
                $subCategory = SubCategory::where('slug', $subCategory)->firstOrFail();
            }

            return VehicleType::where('slug', $value)
                ->where('sub_category_id', $subCategory->id)
                ->firstOrFail();
        });
    }

    protected function configureRateLimiting(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('web', function (Request $request) {
            return Limit::perMinute(120)->by($request->ip());
        });
    }
}
