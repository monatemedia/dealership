<?php // app/Http/Controllers/MainCategoryController.php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\Vehicle;

class MainCategoryController extends Controller
{
    public function index()
    {
        $mainCategories = MainCategory::all();
        $selectingForCreate = session()->has('selecting_category_for_create');

        return view('categories.index', [
            'categories' => $mainCategories,
            'type' => 'Main Category',
            'selectingForCreate' => $selectingForCreate,
        ]);
    }

    public function show(MainCategory $mainCategory)
    {
        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('main_category_id', $mainCategory->id)
            ->latest()
            ->paginate(15);

        $subCategories = SubCategory::with('mainCategory')
            ->where('main_category_id', $mainCategory->id)
            ->take(3)
            ->get();

        return view('categories.show', [
            'category' => $mainCategory,
            'vehicles' => $vehicles,
            'childCategories' => $subCategories,
            'childCategoryType' => 'Sub-Category',
            'parentCategory' => $mainCategory, // Pass mainCategory as parent for button
        ]);
    }
}
