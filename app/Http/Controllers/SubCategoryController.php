<?php
namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\Vehicle;
use App\Models\VehicleType;

class SubCategoryController extends Controller
{
    public function index()
    {
        $subCategories = SubCategory::with('mainCategory')->get();
        $selectingForCreate = session()->has('selecting_category_for_create');

        return view('categories.index', [
            'categories' => $subCategories,
            'type' => 'Sub-Category',
            'selectingForCreate' => $selectingForCreate,
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
            'parentCategory' => $mainCategory,
        ]);
    }
}
