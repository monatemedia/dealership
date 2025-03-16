<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarImage;
use App\Models\Manufacturer;
use App\Models\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\Sequence;

class HomeController extends Controller
{
    public function index()
    {
        // Select latest published 30 cars and sort them by published_at date
        $cars = Car::with(['city', 'carType', 'fuelType', 'manufacturer', 'model', 'primaryImage']) // Eager load the relationships
            ->where('published_at', '<', now()) // Only show cars that are published
            ->orderBy('published_at', 'desc') // Order by the published_at date
            ->limit(30) // Limit the results to 30
            ->get(); // Get the results

        // Return the view with the cars
        return view(
            'home.index', // The view to return
            ['cars' => $cars]
        ); // Pass the cars to the view
    }
}
