{{-- resources/views/components/hero/button.blade.php --}}

@props([
    'href' => null,
    'category' => null,
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
        // Category-aware default (uses DB singular column)
        $singular = $category->singular ?? $category->name;
        // Category-aware default
        $label = 'Sell ' . ucfirst($singular);
        $href = route($routeName, ['category' => $category->slug]);
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
