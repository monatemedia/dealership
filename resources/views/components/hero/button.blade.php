{{-- resources/views/components/hero/button.blade.php --}}
@props([
    'href' => null,
    'section' => null,
    'category' => null,
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
    } elseif ($category) {
        // category aware
        $singular = $category->singular ?? $category->name;
        $label = 'Sell Your ' . $singular;
        $href = route($routeName, ['category' => $category->slug]);
    } elseif ($section) {
        // Section aware
        $singular = $section->singular ?? $section->name;
        $label = 'Sell Your ' . $singular;
        $href = route($routeName, ['section' => $section->slug]);
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
