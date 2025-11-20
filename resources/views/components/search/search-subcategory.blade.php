{{-- resources/views/components/search/search-subcategory.blade.php --}}
<div class="select-wrapper">
    <select
        id="subcategory-select"
        class="form-select"
        x-model="selectedSubcategory"
        x-bind:disabled="!selectedMainCategory"
        x-bind:class="{ 'select-input-disabled': !selectedMainCategory }"
        name="subcategory_id_temp" {{-- Use temp name since the container handles the final hidden input --}}
    >
        <option value="" disabled selected>
            <span x-show="!selectedMainCategory">Select a Main Category first</span>
            <span x-show="selectedMainCategory">Select a Subcategory</span>
        </option>

        <template x-for="subcategory in availableSubcategories" :key="subcategory.id">
            <option x-bind:value="subcategory.id" x-text="subcategory.name"></option>
        </template>
    </select>

    {{-- Custom SVG Icon for the dropdown chevron --}}
    <svg class="select-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
    </svg>
</div>
