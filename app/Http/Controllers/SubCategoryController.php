<?php // app/Http/Controllers/SubCategoryController.php
namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\Vehicle;
use App\Models\VehicleType;

class SubCategoryController extends Controller
{
    /**
     * Show all sub-categories for a main category
     * Route: /{mainCategory}/sub-categories
     */
    public function index(MainCategory $mainCategory)
    {
        $subCategories = SubCategory::with('mainCategory')
            ->where('main_category_id', $mainCategory->id)
            ->get();

        $selectingForCreate = session('selecting_category_for_create', false);

        return view('categories.index', [
            'categories' => $subCategories,
            'type' => 'Sub-Category',
            'selectingForCreate' => $selectingForCreate,
            'parentCategory' => $mainCategory,
        ]);
    }

    public function show(MainCategory $mainCategory, SubCategory $subCategory)
    {
        $subCategory->load('mainCategory');

        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('sub_category_id', $subCategory->id)
            ->latest()
            ->paginate(15);

        $vehicleTypes = VehicleType::where('sub_category_id', $subCategory->id)
            ->take(3)
            ->get();

        return view('categories.show', [
            'category' => $subCategory,
            'vehicles' => $vehicles,
            'childCategories' => $vehicleTypes,
            'childCategoryType' => 'Vehicle Type',
            'parentCategory' => $subCategory, // Pass subCategory as parent for button
        ]);
    }
}
