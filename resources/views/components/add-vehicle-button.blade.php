{{-- resources/views/components/add-vehicle-button.blade.php --}}
@php
use Illuminate\Support\Facades\Route;

// Don't show button on vehicle.create route
$hide = Route::currentRouteNamed('vehicle.create');

// Default text and link
$label = 'Sell Your Vehicle';
$href = route('vehicle.create'); // ← Always start with vehicle.create to trigger the flow

// Determine button behavior based on current context
// If on sub-category page, go directly to create with that sub-category pre-selected
if (isset($subCategory) && $subCategory) {
    $singular = $subCategory->singular ?? $subCategory->name;
    $label = 'Sell Your ' . $singular;
    $href = route('vehicle.create', ['sub_category' => $subCategory->slug]);
}
// If on main category page (but not sub-category), go to sub-categories for selection
elseif (isset($mainCategory) && $mainCategory && !isset($subCategory)) {
    $singular = $mainCategory->singular ?? $mainCategory->name;
    $label = 'Sell Your ' . $singular;
    // Go to sub-categories for this main category in selection mode
    session()->put('selecting_category_for_create', true);
    $href = route('main-category.sub-categories.index', ['mainCategory' => $mainCategory->slug]);
}
// Otherwise (home page, etc), start the flow through vehicle.create
else {
    $label = 'Sell Your Vehicle';
    $href = route('vehicle.create'); // Will redirect to main-categories with session set
}
@endphp

{{-- Hide button on vehicle.create route --}}
@if (!$hide)
    <a href="{{ $href }}" class="btn btn-add-new-vehicle">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" style="width: 18px; margin-right: 4px">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        {{ $label }}
    </a>
@endif
