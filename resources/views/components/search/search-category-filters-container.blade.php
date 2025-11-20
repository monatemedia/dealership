{{-- resources/views/components/search/search-category-filters-container.blade.php --}}
@php
use App\Models\MainCategory;
use App\Models\Subcategory;

// 1. Fetch all Main Categories
$mainCategories = MainCategory::all();

// 2. Fetch all Subcategories, grouped by main_category_id, for dynamic filtering in Alpine
$allSubcategories = Subcategory::all()->groupBy('main_category_id')->toArray();
@endphp

<div
    x-data="{
        // Preloaded data (used as the lookup table)
        allSubcategories: @js($allSubcategories),

        // STATE: Main Category
        selectedMainCategory: @js(request('main_category_id', '')),

        // STATE: Subcategory
        availableSubcategories: [],
        selectedSubcategory: @js(request('subcategory_id', '')),

        /**
         * Watcher Logic: Filters subcategories when a Main Category is selected.
         */
        filterSubcategories() {
            if (this.selectedMainCategory) {
                // Ensure the selectedMainCategory is treated as a string key for lookup
                this.availableSubcategories = this.allSubcategories[this.selectedMainCategory.toString()] || [];
            } else {
                this.availableSubcategories = [];
            }

            // If the currently selected subcategory ID is no longer in the available list, clear it.
            let subcategoryIds = this.availableSubcategories.map(sub => sub.id.toString());
            if (this.selectedSubcategory && !subcategoryIds.includes(this.selectedSubcategory.toString())) {
                this.clearSubcategory();
            }
        },

        /**
         * Clears the subcategory state. Called internally when main category changes.
         */
        clearSubcategory() {
            this.selectedSubcategory = '';
        },

        /**
         * Initialization: Set initial state from URL parameters and set up watcher.
         */
        init() {
            // 1. Run the filter logic once to populate initial subcategories
            this.filterSubcategories();

            // 2. Set up a watcher to re-run filtering whenever the main category changes
            this.$watch('selectedMainCategory', () => {
                this.filterSubcategories();
                this.clearSubcategory(); // Always clear subcategory when main category changes
            });
        }
    }"
    class="category-filters-container"
>
    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="main_category_id" x-model="selectedMainCategory">
    <input type="hidden" name="subcategory_id" x-model="selectedSubcategory">

    <!-- 1. Main Category Selector -->
    <x-search.search-main-category :main-categories="$mainCategories" />

    <!-- 2. Subcategory Selector (Dependent) -->
    <x-search.search-subcategory />
</div>
