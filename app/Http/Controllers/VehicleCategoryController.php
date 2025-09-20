<?php

// app/Http/Controllers/VehicleCategoryController.php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;

class VehicleCategoryController extends Controller
{
    public function index()
    {
        $categories = VehicleCategory::all();

        return view('categories.index', [
            'categories' => $categories,
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
