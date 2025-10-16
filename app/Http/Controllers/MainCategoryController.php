<?php // app/Http/Controllers/MainCategoryController.php

namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\Vehicle;

class MainCategoryController extends Controller
{

    /**
     * MainCategory::index
     * Show all main categories
     */
    public function index()
    {
        $mainCategories = MainCategory::all();
        $selectingForCreate = session()->has('selecting_category_for_create');

        return view('categories.index', [
            'categories' => $mainCategories,
            'selectingForCreate' => $selectingForCreate,
        ]);
    }


    /**
     * Display vehicles and subcategories for a main category.
     */
    public function show(MainCategory $mainCategory)
    {
        // Get vehicles for this main category
        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('main_category_id', $mainCategory->id)
            ->latest()
            ->paginate(15);

        $subCategories = SubCategory::with('mainCategory')
            ->where('main_category_id', $mainCategory->id)
            ->take(3)
            ->get();

        return view('categories.show', [
            'category' => $mainCategory,         // For hero and title
            'vehicles' => $vehicles,             // For vehicle listing
            'childCategories' => $subCategories, // ✅ This drives <x-category.section>
            'childCategoryType' => 'Sub-Category', // ✅ For dynamic heading / route naming
        ]);
    }
}
