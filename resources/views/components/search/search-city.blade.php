{{-- resources/views/components/search/search-city.blade.php --}}
@php
    $provinceEvent = $attributes->get('province-event', 'province-selected');
    $cityEvent = $attributes->get('city-event', 'city-changed');
    // We expect the city ID to be passed via the 'value' prop, but we'll use an explicit prop for clarity if available
    $initialCityId = $attributes->get('initial-city-id');
@endphp
<div x-data="{
    search: '',
    cities: [],
    selected: @js($attributes->get('value')),
    selectedName: '',
    provinceId: null,
    open: false,
    loading: false,

    async searchCities() {
        if (this.search.length < 2) {
            this.cities = [];
            return;
        }
        this.loading = true;
        try {
            let url = `/api/cities/search?q=${encodeURIComponent(this.search)}`;
            // Optional filtering: only add province filter if one is selected
            if (this.provinceId) {
                url += `&province_id=${this.provinceId}`;
                console.log('🔍 Fetching cities filtered by province:', this.provinceId);
            } else {
                console.log('🔍 Fetching all cities (no province filter)');
            }
            const response = await fetch(url);
            this.cities = await response.json();
            console.log('✅ Cities received:', this.cities.length, 'results');
        } catch (error) {
            console.error('Error fetching cities:', error);
        }
        this.loading = false;
    },

    selectCity(id, name) {
        this.selected = id;
        this.selectedName = name;
        this.open = false;
        this.search = name;

        console.log('📍 City selected:', { id, name });
        this.$dispatch('city-selected', { id: id, name: name });
        this.$dispatch('{{ $cityEvent }}', { id: id });
    },

    resetCityOnly() {
        // Only reset city, keep province filter intact
        console.log('🔄 Resetting city selection (keeping province filter)');
        this.search = '';
        this.selected = null;
        this.selectedName = '';
        this.cities = [];
        this.$dispatch('{{ $cityEvent }}', { id: null });
    },

    resetComponent() {
        // Full reset: clear both city and province filter
        console.log('🔄 Full reset: clearing city and province filter');
        this.search = '';
        this.selected = null;
        this.selectedName = '';
        this.provinceId = null;
        this.cities = [];
        this.$dispatch('{{ $cityEvent }}', { id: null });
    },

    closeDropdown() {
        this.open = false;
    },

    async init() {
        // 1. Load initial city if provided (either via 'value' prop or initial-city-id prop)
        const initialId = @js($initialCityId) || this.selected;

        if (initialId) {
            try {
                const response = await fetch(`/api/cities/${initialId}`);
                const data = await response.json();
                this.selected = initialId;
                this.selectedName = data.name;
                this.search = data.name;
                this.provinceId = data.province_id;
                console.log('🏙️ Initial city loaded:', data.name, 'Province ID:', this.provinceId);

                // 🔑 CRITICAL: Dispatch the province event after loading the city,
                // so that the province selector (if it's initialized after the city)
                // can also update its state and pre-select the correct province.
                this.$dispatch('{{ $provinceEvent }}', { id: this.provinceId, name: data.province.name });

            } catch (error) {
                console.error('Error fetching initial city:', error);
            }
        }

        // Listen for filter reset
        window.addEventListener('filters-reset', () => {
            this.resetComponent();
        });

        // Watch for manual clearing
        this.$watch('search', (value) => {
            if (value === '' && this.selected !== null) {
                this.resetCityOnly();
            }
        });
    }
}"
x-init="
    console.log('🏙️ City component initialized. Listening for event:', '{{ $provinceEvent }}');
    console.log('🏙️ Initial provinceId:', provinceId);
    init();
    console.log('🏙️ City component initialized. Listening for event:', '{{ $provinceEvent }}');
    init();

    // Listen to the province event dynamically
    window.addEventListener('{{ $provinceEvent }}', (event) => {
        console.log('🗺️ Province event received in city component:', event.detail);
        const newProvinceId = event.detail.id;

        // Check if province was cleared (null, empty string, or undefined)
        if (!newProvinceId || newProvinceId === '' || newProvinceId === null) {
            console.log('🗑️ Province cleared - resetting city component completely');
            provinceId = null;
            resetCityOnly();
        } else {
            console.log('🗺️ provinceId updated to:', newProvinceId);
            provinceId = newProvinceId;
            resetCityOnly();
        }
    });
"
@click.outside="closeDropdown()"
class="select-container">
    <input type="hidden" name="{{ $attributes->get('name', 'city_id') }}" x-model="selected">
    <input
        type="text"
        x-model="search"
        @input.debounce.300ms="searchCities()"
        @focus="open = true"
        @blur="setTimeout(() => { if (!$el.closest('[x-data]').querySelector('.select-dropdown:hover')) closeDropdown() }, 150)"
        placeholder="Select City"
        class="select-input"
        autocomplete="off"
    >
    <div
        x-show="open"
        x-transition
        class="select-dropdown"
    >
        <div class="select-list">
            <template x-if="loading">
                <div class="select-info">Loading...</div>
            </template>
            <template x-if="!loading && search.length < 2">
                <div class="select-info">
                    <span x-show="provinceId">Type at least 2 characters to search cities in selected province</span>
                    <span x-show="!provinceId">Type at least 2 characters to search all cities</span>
                </div>
            </template>
            <template x-if="!loading && cities.length === 0 && search.length >= 2">
                <div class="select-info">
                    <span x-show="provinceId">No cities found in selected province</span>
                    <span x-show="!provinceId">No cities found</span>
                </div>
            </template>
            <template x-for="city in cities" :key="city.id">
                <button
                    type="button"
                    @click="selectCity(city.id, city.name)"
                    class="select-item"
                    x-text="city.name"
                ></button>
            </template>
        </div>
    </div>
</div>
