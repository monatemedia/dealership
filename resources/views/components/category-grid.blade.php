{{-- resources/views/components/category-grid.blade.php --}}

<div class="category-grid container">
    @foreach ($categories as $category)
        <x-category-card
            :href="route('category.show', $category->slug)"
            :image="$category->image_path"
            :title="$category->long_name ?? $category->name"
            :description="$category->description"
        />
    @endforeach
</div>
