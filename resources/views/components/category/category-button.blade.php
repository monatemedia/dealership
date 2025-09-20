{{-- resources/views/components/category/category-button.blade.php --}}

@php
use Illuminate\Support\Facades\Route;

// Hide if we are on the categories.index route
$hide = Route::currentRouteNamed('categories.index');
@endphp

@if(! $hide)
<div class="category-button">
    <a href="{{ route('categories.index') }}" class="btn btn-hero-slider">
        All Categories
    </a>
</div>
@endif
