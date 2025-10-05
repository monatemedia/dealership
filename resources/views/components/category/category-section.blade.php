{{-- resources/views/components/category/category-section.blade.php --}}
@props(['categories', 'selectingForCreate' => false])

@php
    use Illuminate\Support\Facades\Route;

    $isCategoriesIndex = Route::currentRouteNamed('categories.index');

    $tag = $isCategoriesIndex ? 'h1' : 'h2';

    // Custom title and paragraph when selecting for create
    if ($selectingForCreate) {
        $title = 'Select a <strong>Category</strong>';
        $paragraph = 'Choose the category that best matches your vehicle <br> to create your listing';
    } elseif ($isCategoriesIndex) {
        $title = 'All <strong>Categories</strong>';
        $paragraph = 'Experience the pinnacle of quality <br> with our carefully curated vehicle categories.';
    } else {
        $title = 'Popular <strong>Categories</strong>';
        $paragraph = 'See the most popular categories';
    }
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
