<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    public function index()
    {
        $carData = [
            'manufacturer_id' => 1,
            'model_id' => 1,
            'year' => 2024,
            'price' => 20000,
            'vin' => '999',
            'mileage' => 5000,
            'car_type_id' => 1,
            'fuel_type_id' => 1,
            'user_id' => 1,
            'city_id' => 1,
            'address' => 'Something',
            'phone' => '999',
            'description' => null,
            'published_at' => now(),
        ];

        // Create and return record
        $car1 = Car::create($carData);

        // Return the blade view
        return view('home.index');
    }
}
