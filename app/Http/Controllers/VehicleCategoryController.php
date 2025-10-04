<?php // app/Http/Controllers/VehicleCategoryController.php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;

class VehicleCategoryController extends Controller
{
    public function index()
    {
        $categories = VehicleCategory::all();

        // Check if redirected from vehicle.create for category selection
        // Use session()->get() to retrieve flashed data
        $selectingForCreate = session()->has('selecting_category_for_create');

        return view('categories.index', [
            'categories' => $categories,
            'selectingForCreate' => $selectingForCreate,
        ]);
    }

    public function show(VehicleCategory $category)
    {
        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('vehicle_category_id', $category->id)
            ->latest()
            ->paginate(15);

        $categories = VehicleCategory::take(3)->get();

        return view('home.index', [   // reuse resources/views/home.blade.php
            'vehicles' => $vehicles,
            'categories' => $categories,
        ]);
    }
}
