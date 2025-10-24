<?php
// app/Http/Controllers/MainCategoryController.php
namespace App\Http\Controllers;

use App\Models\MainCategory;
use App\Models\Subcategory;
use App\Models\Vehicle;

class MainCategoryController extends Controller
{
    public function index()
    {
        $mainCategories = MainCategory::all();
        $selectingForCreate = session('selecting_category_for_create', false);

        // **FIX:** When selecting, the "create" route is the *next step* // (the sub-category list), not the *final* step (vehicle.create).

        $createRouteName = $selectingForCreate
            ? 'main-category.sub-categories.index' // Next step in the flow
            : 'main-categories.show'; // Default "show" action

        $createRouteParam = $selectingForCreate
            ? 'mainCategory' // The param name for the sub-category route
            : 'mainCategory'; // The param name for the show route

        // If not selecting, just use the standard show route
        if (!$selectingForCreate) {
             $createRouteName = 'main-categories.show';
        }

        return view('categories.index', [
            'categories' => $mainCategories,
            'type' => 'Main Category',
            'pluralType' => 'Main Categories', // Add this for the component title
            'selectingForCreate' => $selectingForCreate,

            // **PASS THE CORRECT PROPS TO THE VIEW**
            'indexRouteName' => 'main-categories.index',
            'showRouteName' => 'main-categories.show',
            'createRouteName' => $createRouteName,
            'createRouteParam' => $createRouteParam,
        ]);
    }

    public function show(MainCategory $mainCategory)
    {
        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('main_category_id', $mainCategory->id)
            ->latest()
            ->paginate(15);

        $subcategories = Subcategory::with('mainCategory')
            ->where('main_category_id', $mainCategory->id)
            ->take(3)
            ->get();

        return view('categories.show', [
            'category' => $mainCategory,
            'vehicles' => $vehicles,
            'childCategories' => $subcategories,
            'childCategoryType' => 'Sub-Category',
            'parentCategory' => $mainCategory,
        ]);
    }
}
