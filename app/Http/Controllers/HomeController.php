<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $cars = Cache::remember('home-cars', 60, function () {
            return Car::where('published_at', '<', now())
                ->with(['primaryImage', 'city', 'carType', 'fuelType', 'manufacturer', 'model', 'favouredUsers'])
                ->orderBy('published_at', 'desc')
                ->limit(30)
                ->get();
        });

        // Return the view with the cars
        return view(
            'home.index', // The view to return
            ['cars' => $cars]
        ); // Pass the cars to the view
    }
}
