{{-- resources/views/components/search/search-main-category.blade.php --}}
@props(['mainCategories'])

<div class="select-wrapper">
    <select
        id="main-category-select"
        class="form-select"
        x-model="selectedMainCategory"
        name="main_category_id_temp" {{-- Use temp name since the container handles the final hidden input --}}
    >
        <option value="" disabled selected>Select a Main Category</option>
        @foreach($mainCategories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>

    {{-- Custom SVG Icon for the dropdown chevron --}}
    <svg class="select-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
    </svg>
</div>
