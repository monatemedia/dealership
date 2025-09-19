<?php

namespace App\Providers;

use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Set the default pagination view
        Paginator::defaultView('pagination');

        // Share the year with all views
        View::share('year', date('Y'));

        // Share the category with all views
        View::composer('*', function ($view) {
            $category = request()->route('category') ?? null;
            $view->with('category', $category);
        });

        // // Define a gate to check if the user can update a vehicle
        // Gate::define('update-vehicle', function (User $user, Vehicle $vehicle) {
        //     // Allow the user to update the vehicle if they are the owner
        //     return $user->id === $vehicle->user_id ? Response::allow()
        //         // Deny access with a 404 status if they are not the owner
        //         : Response::denyWithStatus(404);
        // });

        // // Define a gate to check if the user can delete a vehicle
        // Gate::define('delete-vehicle', function (User $user, Vehicle $vehicle) {
        //     // Allow the user to delete the vehicle if they are the owner
        //     return $user->id === $vehicle->user_id ? Response::allow()
        //         // Deny access with a 404 status if they are not the owner
        //         : Response::denyWithStatus(404);
        // });
    }
}
