{{-- resources/views/components/add-vehicle-button.blade.php --}}
@php
use Illuminate\Support\Facades\Route;

// Don't show button on vehicle.create route
$hide = Route::currentRouteNamed('vehicle.create');

// Default link and label
$href = route('vehicle.create'); // Always start the flow
$label = 'Sell Your Vehicle';

// If on a main category page, pass it as query parameter
if (isset($mainCategory) && $mainCategory) {
    $href = route('vehicle.create', ['main_category' => $mainCategory->slug]);
    $label = 'Sell Your ' . ($mainCategory->singular ?? $mainCategory->name);
}

// If on a sub-category page, pass it as query parameter
if (isset($subCategory) && $subCategory) {
    $href = route('vehicle.create', ['sub_category' => $subCategory->slug]);
    $label = 'Sell Your ' . ($subCategory->singular ?? $subCategory->name);
}
@endphp

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
