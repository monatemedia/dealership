{{-- resources/views/components/hero/button.blade.php --}}
@props([
    'href' => null,
    'mainCategory' => null,
    'subcategory' => null,
    'vehicleType' => null,
    'routeName' => 'vehicle.create',
    'hideOnRoute' => null,
])

@php
    use Illuminate\Support\Facades\Route;

    $hide = $hideOnRoute ? Route::currentRouteNamed($hideOnRoute) : false;

    // Determine label and href
    if (! $slot->isEmpty()) {
        // Use slot text if provided
        $label = trim($slot);
        $href = $href ?? route($routeName);
    } elseif ($subcategory) {
        // Sub-category aware
        $singular = $subcategory->singular ?? $subcategory->name;
        $label = 'Sell Your ' . $singular;
        $href = route($routeName, ['subcategory' => $subcategory->slug]);
    } elseif ($mainCategory) {
        // Main category aware
        $singular = $mainCategory->singular ?? $mainCategory->name;
        $label = 'Sell Your ' . $singular;
        $href = route($routeName, ['main_category' => $mainCategory->slug]);
    } elseif ($vehicleType) {
        // Vehicle type aware
        $label = 'Sell Your ' . $vehicleType->name;
        $href = $href ?? route($routeName);
    } else {
        // Fallback
        $label = 'Sell Your Vehicle';
        $href = $href ?? route($routeName);
    }
@endphp

@if (! $hide)
    <a href="{{ $href }}" class="btn btn-hero-slider">
        {{ $label }}
    </a>
@endif
