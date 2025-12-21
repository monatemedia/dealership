@php
use Illuminate\Support\Facades\Route;

// Determine if user is in the create flow
$inCreateFlow = Route::currentRouteNamed('vehicle.create')
    || (session('selecting_category_for_create', false) && session('from_vehicle_create', false));

// Default text and link
$label = 'Sell Your Vehicle';
$href = route('vehicle.create');

// If on category page
if (isset($category) && $category) {
    $label = 'Sell Your ' . ($category->singular ?? $category->name);
    $href = route('vehicle.create', ['category' => $category->slug]);
}
// If on section page
elseif (isset($section) && $section) {
    $label = 'Sell Your ' . ($section->singular ?? $section->name);
    $href = route('vehicle.create', ['section' => $section->slug]);
}
@endphp

@if (! $inCreateFlow)
    <a href="{{ $href }}" class="btn btn-add-new-vehicle">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" style="width: 18px; margin-right: 4px">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        {{ $label }}
    </a>
@endif
