<?php

namespace App\Providers;

use App\Models\Car;
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

        // Define a gate to check if the user can update a car
        Gate::define('update-car', function (User $user, Car $car) {
            // Allow the user to update the car if they are the owner
            return $user->id === $car->user_id ? Response::allow()
                // Deny access with a 404 status if they are not the owner
                : Response::denyWithStatus(404);
        });

        // Define a gate to check if the user can delete a car
        Gate::define('delete-car', function (User $user, Car $car) {
            // Allow the user to delete the car if they are the owner
            return $user->id === $car->user_id ? Response::allow()
                // Deny access with a 404 status if they are not the owner
                : Response::denyWithStatus(404);
        });
    }
}
