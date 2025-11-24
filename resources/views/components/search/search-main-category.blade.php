{{-- resources/views/components/search/search-main-category.blade.php --}}
@props(['mainCategories', 'value' => null])

<div class="select-container w-full">
    <select
        name="main_category_id"
        class="select-input"
        {{-- Captures x-model="selectedMainCategory" from parent --}}
        {{ $attributes->whereStartsWith('x-') }}
        {{-- 🔑 DIAGNOSTIC: Log dispatch and send the event --}}
        @change="
            const id = $event.target.value;
            console.log('MAIN CATEGORY DISPATCHED:', id);
            $dispatch('main-category-selected', { id: id })
        "
    >
        <option value="">Main Category</option>
        @foreach ($mainCategories as $category)
            <option
                value="{{ $category->id }}"
                @selected($value == $category->id)>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
</div>
