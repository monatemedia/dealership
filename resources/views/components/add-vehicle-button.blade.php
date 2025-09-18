{{-- resources/views/components/add-vehicle-button.blade.php --}}

@php
    use Illuminate\Support\Facades\Route;

    // Don’t show button on vehicle.create route
    $hide = Route::currentRouteNamed('vehicle.create');

    // Default text and link
    $label = 'Sell Your Vehicle';
    $href = route('vehicle.create');

    // If on category page, override text + add query string
    if ($category) {
        // Pull category info directly from config
        $categoryConfig = config('vehicles.categories.' . $category->name);

        // Use the singular from config, fallback to $category->name
        $singular = $categoryConfig['singular'] ?? $category->name;

        $label = 'Sell ' . ucfirst($singular);
        $href = route('vehicle.create', ['category' => $category->slug]);
    }
@endphp

{{-- Hide button on vehicle.create route --}}
@if (! $hide)
    <a href="{{ $href }}" class="btn btn-add-new-vehicle">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" style="width: 18px; margin-right: 4px">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
        {{ $label }}
    </a>
@endif
