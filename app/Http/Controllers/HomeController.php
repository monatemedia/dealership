<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Manufacturer;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Sequence;

class HomeController extends Controller
{
    public function index()
    {
        // Get the latest 30 cars
        $cars = Car::where('published_at', '<', now()) // Only show cars that are published
            ->orderBy('published_at', 'desc') // Order by the newest cars
            ->limit(30) // Limit to 30 cars
            ->get(); // Get the cars

        // Return the view with the cars
        return view(
            'home.index', // The view to return
            ['cars' => $cars]
        ); // Pass the cars to the view
    }
}
