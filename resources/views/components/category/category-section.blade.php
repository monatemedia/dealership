{{-- resources/views/components/category/category-section.blade.php --}}
@props(['categories', 'selectingForCreate' => false])

{{-- Debug: Check if prop is received --}}
@dump($selectingForCreate)

@php
    use Illuminate\Support\Facades\Route;

    $isCategoriesIndex = Route::currentRouteNamed('categories.index');

    $tag = $isCategoriesIndex ? 'h1' : 'h2';
    $title = $isCategoriesIndex
        ? 'All <strong>Categories</strong>'
        : 'Popular <strong>Categories</strong>';
    $paragraph = $isCategoriesIndex
        ? 'Experience the pinnacle of quality <br> with our carefully curated vehicle categories.'
        : 'See the most popular categories';
@endphp

<section @class([
    'category-section',
    'category-section-no-padding' => ! $isCategoriesIndex,
])>
    <x-title
        :tag="$tag"
        :title="$title"
        :paragraph="$paragraph"
    />

    <x-category.category-grid
        :categories="$categories"
        :selectingForCreate="$selectingForCreate ?? false"
    />

    <x-category.category-button />
</section>
