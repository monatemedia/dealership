{{-- resources/views/components/search/search-fuel-type.blade.php --}}
@props(['value' => null])
@php
    $initialSubcatId = request('subcategory_id', '');
@endphp

<div class="select-container"
    x-data="{
        // State for dynamic content
        parentSubcatId: '{{ $initialSubcatId }}',
        options: [],
        isLoading: false,
        selectedFuelTypeId: '{{ request('fuel_type_id', '') }}',

        // Computed check for enabled state
        isEnabled() { return this.parentSubcatId !== ''; },

        // Fetch fuel types from the API
        async fetchOptions(subcatId) {
            this.parentSubcatId = subcatId;
            this.options = [];

            if (!this.isEnabled()) {
                this.selectedFuelTypeId = '';
                return;
            }

            this.isLoading = true;
            try {
                const response = await fetch(`/api/fuel-types-by-sub/${subcatId}`);
                if (response.ok) {
                    this.options = await response.json();
                } else {
                    console.error('Failed to fetch fuel types.');
                    this.options = [];
                }
            } catch (error) {
                console.error('Error fetching fuel types:', error);
                this.options = [];
            } finally {
                this.isLoading = false;
            }
        }
    }"
    x-init="
        if (parentSubcatId) { fetchOptions(parentSubcatId); }
        $nextTick(() => {
            window.addEventListener('subcategory-selected', (e) => {
                fetchOptions(e.detail.id);
            });
        });
    "
>
    <select
        name="fuel_type_id"
        class="select-input"
        x-model="selectedFuelTypeId"
        {{-- Bind disabled state (disabled if not enabled OR if loading) --}}
        x-bind:disabled="!isEnabled() || isLoading"
        {{-- Bind classes for greying out --}}
        x-bind:class="{
            'opacity-50 cursor-not-allowed': !isEnabled() || isLoading,
            'select-input': true
        }"
    >
        <option value="" x-text="isLoading ? 'Loading...' : 'Fuel Type'"></option>
        <template x-for="option in options" :key="option.id">
            <option :value="option.id" x-text="option.name" :selected="option.id == selectedFuelTypeId"></option>
        </template>
    </select>
</div>
