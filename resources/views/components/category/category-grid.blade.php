{{-- resources/views/components/category-grid.blade.php --}}
@props(['categories', 'selectingForCreate' => false])

<div class="category-grid container">
    @foreach ($categories as $category)
        @php
            // If selecting for create, link to create form, otherwise to listings
            $href = $selectingForCreate
                ? route('vehicle.create', ['category' => $category->slug])
                : route('category.show', $category->slug);
        @endphp

        <x-category.category-card
            :href="$href"
            :image="$category->image_path"
            :title="$category->long_name ?? $category->name"
            :description="$category->description"
            :categorySlug="$category->slug"
            :selectingForCreate="$selectingForCreate"
        />
    @endforeach
</div>
