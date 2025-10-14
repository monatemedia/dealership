<?php
// app/Http/Controllers/SubCategoryController.php
namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\SubCategory;
use App\Models\Vehicle;

class SubCategoryController extends Controller
{
    /**
     * Show all sub-categories (for main category selection in create flow)
     */
    public function index()
    {
        $subCategories = SubCategory::all();
        $selectingForCreate = session()->has('selecting_category_for_create');

        return view('sub-categories.index', [
            'categories' => $subCategories,
            'selectingForCreate' => $selectingForCreate,
        ]);
    }

    /**
     * Show vehicles filtered by sub-category
     */
    public function show(SubCategory $subCategory)
    {
        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('sub_category_id', $subCategory->id)
            ->latest()
            ->paginate(15);

        $mainCategories = MainCategory::take(3)->get();

        return view('sub-categories.show', [
            'subCategory' => $subCategory,
            'mainCategory' => $subCategory->mainCategory,
            'vehicles' => $vehicles,
            'categories' => $mainCategories,
        ]);
    }
}
