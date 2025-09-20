{{-- resources/views/components/category/category-section.blade.php --}}

@php
    use Illuminate\Support\Facades\Route;
@endphp

<section @class([
    'category-section',
    'category-section-no-padding' => !Route::currentRouteNamed('categories.index'),
])>

    <x-title
        tag="h1"
        title="All <strong>Categories</strong>"
        paragraph="Experience the pinnacle of quality <br> with our carefully curated vehicle categories."
    />

    <x-category.category-grid :categories=$categories />

    <x-category.category-button />

</section>
