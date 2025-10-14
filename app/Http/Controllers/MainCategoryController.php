<?php
// app/Http/Controllers/MainCategoryController.php
namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\Vehicle;

class MainCategoryController extends Controller
{
    /**
     * Show all main categories
     */
    public function index()
    {
        $mainCategories = MainCategory::all();
        $selectingForCreate = session()->has('selecting_category_for_create');

        return view('main-categories.index', [
            'categories' => $mainCategories,
            'selectingForCreate' => $selectingForCreate,
        ]);
    }

    /**
     * Show vehicles filtered by main category
     */
    public function show(MainCategory $mainCategory)
    {
        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('main_category_id', $mainCategory->id)
            ->latest()
            ->paginate(15);

        $mainCategories = MainCategory::take(3)->get();

        return view('main-categories.show', [
            'mainCategory' => $mainCategory,
            'vehicles' => $vehicles,
            'categories' => $mainCategories,
        ]);
    }
}
