{{-- resources/views/components/category/button.blade.php --}}
@props(['text', 'href', 'hideOnRoute' => null])

@php
    use Illuminate\Support\Facades\Route;
    $isHidden = $hideOnRoute && Route::currentRouteNamed($hideOnRoute);
@endphp

@if(! $isHidden)
    <div class="category-button">
        <a href="{{ $href }}" class="btn btn-hero-slider">
            {{ $text }}
        </a>
    </div>
@endif
