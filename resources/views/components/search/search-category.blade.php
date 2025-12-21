{{-- resources/views/components/search/search-category.blade.php --}}
@props(['value' => null])
@php
    $initialId = request('section_id', '');
@endphp

<div class="select-container w-full"
    x-data="{
        // State for dynamic content
        parentMainCatId: '{{ $initialId }}',
        options: [],
        isLoading: false,
        selectedCategoryId: '{{ request('category_id', '') }}',

        // Computed check for enabled state
        isEnabled() {
            const enabled = this.parentMainCatId !== '';
            // DIAGNOSTIC: Log computed state
            console.log('SUBCAT enabled status (ID:', this.parentMainCatId, '):', enabled);
            return enabled;
        },

        // Fetch categories from the API
        async fetchOptions(mainCatId) {
            // DIAGNOSTIC: Log when fetchOptions is called
            console.log('SUBCAT fetchOptions called with:', mainCatId);

            this.parentMainCatId = mainCatId;
            this.options = [];
            this.selectedCategoryId = '';

            if (!this.isEnabled()) {
                this.$dispatch('category-selected', { id: '' });
                return;
            }

            this.isLoading = true;
            try {
                // IMPORTANT: Confirm this API route is correct in your routes/api.php
                const response = await fetch(`/api/categories-by-main/${mainCatId}`);
                if (response.ok) {
                    this.options = await response.json();
                    console.log('SUBCAT API Success. Options count:', this.options.length);
                } else {
                    console.error('Failed to fetch categories. Status:', response.status);
                    this.options = [];
                }
            } catch (error) {
                console.error('Error fetching categories:', error);
                this.options = [];
            } finally {
                this.isLoading = false;
            }
            this.$dispatch('category-selected', { id: this.selectedCategoryId });
        }
    }"
    x-init="
        if (parentMainCatId) { fetchOptions(parentMainCatId); }

        $nextTick(() => {
            window.addEventListener('section-selected', (e) => {
                console.log('SUBCAT RECEIVED EVENT (JS listener):', e.detail.id);
                fetchOptions(e.detail.id);
            });
            // 🔑 NEW: Listen for the general reset event (calls fetchOptions with empty ID)
            window.addEventListener('filters-reset', () => {
                fetchOptions('');
            });
        });
    "
>
    <select
        name="category_id"
        class="select-input"
        x-model="selectedCategoryId"
        @change="$dispatch('category-selected', { id: $event.target.value })"
        {{-- Bind disabled state (disabled if not enabled OR if loading) --}}
        x-bind:disabled="!isEnabled() || isLoading"
        {{-- Bind classes for greying out --}}
        x-bind:class="{
            'opacity-50 cursor-not-allowed': !isEnabled() || isLoading,
            'select-input': true
        }"
    >
        <option value="" x-text="isLoading ? 'Loading...' : 'Category'"></option>
        <template x-for="option in options" :key="option.id">
            <option :value="option.id" x-text="option.name" :selected="option.id == selectedCategoryId"></option>
        </template>
    </select>
</div>
