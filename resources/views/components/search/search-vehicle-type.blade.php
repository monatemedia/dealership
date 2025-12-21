{{-- resources/views/components/search/search-vehicle-type.blade.php --}}
@props(['value' => null])
@php
    $initialSubcatId = request('category_id', '');
@endphp

<div class="select-container w-full"
    x-data="{
        // State for dynamic content
        parentSubcatId: '{{ $initialSubcatId }}',
        options: [],
        isLoading: false,
        selectedTypeId: '{{ request('vehicle_type_id', '') }}',

        // Computed check for enabled state
        isEnabled() { return this.parentSubcatId !== ''; },

        // Fetch vehicle types from the API
        async fetchOptions(subcatId) {
            this.parentSubcatId = subcatId;
            this.options = [];

            if (!this.isEnabled()) {
                this.selectedTypeId = '';
                return;
            }

            this.isLoading = true;
            try {
                const response = await fetch(`/api/vehicle-types-by-sub/${subcatId}`);
                if (response.ok) {
                    this.options = await response.json();
                } else {
                    console.error('Failed to fetch vehicle types.');
                    this.options = [];
                }
            } catch (error) {
                console.error('Error fetching vehicle types:', error);
                this.options = [];
            } finally {
                this.isLoading = false;
            }
        }
    }"
    x-init="
        if (parentSubcatId) { fetchOptions(parentSubcatId); }
        $nextTick(() => {
            window.addEventListener('category-selected', (e) => {
                fetchOptions(e.detail.id);
            });
        });
    "
>
    <select
        name="{{ $attributes->get('name', 'vehicle_type_id') }}"
        class="select-input"
        x-model="selectedTypeId"
        {{-- Bind disabled state (disabled if not enabled OR if loading) --}}
        x-bind:disabled="!isEnabled() || isLoading"
        {{-- Bind classes for greying out --}}
        x-bind:class="{
            'opacity-50 cursor-not-allowed': !isEnabled() || isLoading,
            'select-input': true
        }"
    >
        <option value="" x-text="isLoading ? 'Loading...' : 'Body Type'"></option>
        <template x-for="option in options" :key="option.id">
            <option :value="option.id" x-text="option.name" :selected="option.id == selectedTypeId"></option>
        </template>
    </select>
</div>
