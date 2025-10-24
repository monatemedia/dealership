<?php // app/Http/Controllers/SubcategoryController.php
namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\Subcategory;
use App\Models\Vehicle;
use App\Models\VehicleType;

class SubcategoryController extends Controller
{
    /**
     * Show all sub-categories for a main category
     * Route: /{mainCategory}/sub-categories
     */
    public function index(MainCategory $mainCategory)
    {
        $subcategories = Subcategory::with('mainCategory')
            ->where('main_category_id', $mainCategory->id)
            ->get();

        $selectingForCreate = session('selecting_category_for_create', false);

        return view('categories.index', [
            'categories' => $subcategories,
            'type' => 'Sub-Category',
            'selectingForCreate' => $selectingForCreate,
            'parentCategory' => $mainCategory,
        ]);
    }

    public function show(MainCategory $mainCategory, Subcategory $subCategory)
    {
        $subCategory->load('mainCategory');

        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('subcategory_id', $subCategory->id)
            ->latest()
            ->paginate(15);

        $vehicleTypes = VehicleType::where('subcategory_id', $subCategory->id)
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
