{{-- resources/views/components/search/search-city.blade.php --}}
@php
    $provinceEvent = $attributes->get('province-event', 'province-selected');
    $cityEvent = $attributes->get('city-event', 'city-changed'); // Capture the dynamic event name prop
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
            if (this.provinceId) {
                url += `&province_id=${this.provinceId}`;
            }
            const response = await fetch(url);
            this.cities = await response.json();
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

        // Dispatch the city-selected event (for map/modal)
        this.$dispatch('city-selected', { id: id, name: name });

        // 🔑 FIX: Dispatch the dynamic event name (e.g., 'city-filter-selected')
        // This ensures the search-range-slider listens correctly.
        this.$dispatch('{{ $cityEvent }}', { id: id });
    },

    // 🔑 NEW: Helper function to clear component state
    resetComponent() {
        this.search = '';
        this.selected = null; // Clear the actual ID
        this.selectedName = '';
        this.provinceId = null;
        this.cities = [];
        // Important: Dispatch null/empty to force the range slider to clear its max range
        this.$dispatch('{{ $cityEvent }}', { id: null });
    },

    async init() {
        if (this.selected) {
            try {
                const response = await fetch(`/api/cities/${this.selected}`);
                const data = await response.json();
                this.selectedName = data.name;
                this.search = data.name;
                this.provinceId = data.province_id;
            } catch (error) {
                console.error('Error fetching initial city:', error);
            }
        }

        // 🔑 NEW: Listen for global form reset event
        window.addEventListener('filters-reset', () => {
            this.resetComponent();
        });

        // 🔑 NEW: Watch for manual input clearing
        this.$watch('search', (value) => {
            if (value === '' && this.selected !== null) {
                this.resetComponent();
            }
        });
    }
}"
{{-- Dynamically set the event listener name using the province-event attribute. --}}
@php echo "@" . $provinceEvent . ".window=\"provinceId = \$event.detail.id; resetComponent()\"" @endphp
@click.away="open = false"
class="select-container">
    <input type="hidden" name="{{ $attributes->get('name', 'city_id') }}" x-model="selected">
    <input
        type="text"
        x-model="search"
        @input.debounce.300ms="searchCities()"
        @focus="open = true"
        placeholder="Select City"
        class="select-input"
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
                <div class="select-info">Type at least 2 characters to search</div>
            </template>
            <template x-if="!loading && cities.length === 0 && search.length >= 2">
                <div class="select-info">No cities found</div>
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
