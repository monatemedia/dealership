<?php
// app/Http/Controllers/SectionController.php
namespace App\Http\Controllers;

use App\Models\Section;
use App\Models\Subcategory;
use App\Models\Vehicle;

class SectionController extends Controller
{
    public function index()
    {
        $Sections = Section::all();
        $selectingForCreate = session('selecting_category_for_create', false);

        // **FIX:** When selecting, the "create" route is the *next step* // (the sub-category list), not the *final* step (vehicle.create).

        $createRouteName = $selectingForCreate
            ? 'section.sub-categories.index' // Next step in the flow
            : 'sections.show'; // Default "show" action

        $createRouteParam = $selectingForCreate
            ? 'section' // The param name for the sub-category route
            : 'section'; // The param name for the show route

        // If not selecting, just use the standard show route
        if (!$selectingForCreate) {
             $createRouteName = 'sections.show';
        }

        return view('categories.index', [
            'categories' => $Sections,
            'type' => 'Section',
            'pluralType' => 'Sections', // Add this for the component title
            'selectingForCreate' => $selectingForCreate,

            // **PASS THE CORRECT PROPS TO THE VIEW**
            'indexRouteName' => 'sections.index',
            'showRouteName' => 'sections.show',
            'createRouteName' => $createRouteName,
            'createRouteParam' => $createRouteParam,
        ]);
    }

    public function show(Section $Section)
    {
        $vehicles = Vehicle::with(['primaryImage', 'manufacturer', 'model'])
            ->where('section_id', $Section->id)
            ->latest()
            ->paginate(15);

        $subcategories = Subcategory::with('Section')
            ->where('section_id', $Section->id)
            ->take(3)
            ->get();

        return view('categories.show', [
            'category' => $Section,
            'vehicles' => $vehicles,
            'childCategories' => $subcategories,
            'childCategoryType' => 'Subcategory',
            'parentCategory' => $Section,
        ]);
    }
}
