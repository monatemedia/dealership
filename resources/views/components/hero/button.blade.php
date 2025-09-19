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

    // Prioritize slot text if provided
    if (! $slot->isEmpty()) {
        $label = trim($slot);
        $href = $href ?? route($routeName);
    } elseif ($category) {
        $categoryConfig = config('vehicles.categories.' . $category->name, []);
        $singular = $categoryConfig['singular'] ?? $category->name;
        $label = 'Sell ' . ucfirst($singular);
        $href = route($routeName, ['category' => $category->slug]);
    } else {
        $label = 'Sell Your Vehicle';
        $href = $href ?? route($routeName);
    }
@endphp

@if (! $hide)
    <a href="{{ $href }}" class="btn btn-hero-slider">
        {{ $label }}
    </a>
@endif
