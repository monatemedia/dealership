{{-- resources/views/components/search/search-city.blade.php (Local Storage Read) --}}
@php
    $citySelectedEvent = $attributes->get('city-selected-event', 'city-selected');
    // We need the Local Storage keys defined in the parent/global context
    $cityIdKey = 'geo_filter_city_id';
    $cityNameKey = 'geo_filter_city_name';
@endphp
<div
    x-data="{
        search: '',
        cities: [],
        selected: null,
        selectedName: '',
        open: false,
        loading: false,

        async searchCities() {
            if (this.search.length < 2) {
                this.cities = [];
                return;
            }
            this.loading = true;
            try {
                let url = `/api/cities/search?q=${encodeURIComponent(this.search)}&with_province=true`;
                const response = await fetch(url);
                let results = await response.json();
                this.cities = results.map(city => ({
                    id: city.id,
                    name: `${city.name}, ${city.province.name}`,
                    provinceName: city.province.name
                }));
                console.log('✅ Cities received and formatted:', this.cities.length, 'results');
            } catch (error) {
                console.error('Error fetching cities:', error);
            }
            this.loading = false;
        },

        selectCity(city) {
            this.selected = city.id;
            this.selectedName = city.name;
            this.open = false;
            this.search = city.name;
            console.log('📍 City selected:', city);
            this.$dispatch('{{ $citySelectedEvent }}', {
                id: city.id,
                fullName: city.name,
                provinceName: city.provinceName
            });
        },

        resetCity() {
            console.log('🔄 Resetting city selection');
            this.search = '';
            this.selected = null;
            this.selectedName = '';
            this.cities = [];
            this.$dispatch('{{ $citySelectedEvent }}', { id: null, fullName: '', provinceName: '' });
        },

        closeDropdown() {
            this.open = false;
        },

        selectAll(event) {
            event.target.select();
        },

        // New init logic relies on Local Storage first
        async init() {
            // Attempt to read the value from the parent's x-bind first (for first load/cleared state)
            let cityId = this.$el.querySelector('input[type=\'hidden\']').value;
            let cityName = null;

            // Fallback 1: Read directly from Local Storage, which is more reliable on reload
            if (!cityId || cityId === '' || cityId === '0') {
                cityId = localStorage.getItem('{{ $cityIdKey }}');
                cityName = localStorage.getItem('{{ $cityNameKey }}');
            }

            if (cityId && cityId !== '' && cityId !== '0') {
                this.selected = parseInt(cityId);

                if (cityName) {
                    // If the name is already in Local Storage, use it immediately
                    this.selectedName = cityName;
                    this.search = cityName;
                    console.log('🏙️ Initial city loaded from Local Storage:', cityName);
                } else {
                    // Fallback 2: Fetch the name via API if only the ID is present
                    try {
                        const response = await fetch(`/api/cities/${this.selected}`);
                        const data = await response.json();
                        const fullName = `${data.name}, ${data.province.name}`;
                        this.selectedName = fullName;
                        this.search = fullName;
                        console.log('🏙️ Initial city loaded via API fetch:', fullName);
                    } catch (error) {
                        console.error('Error fetching initial city:', error);
                        this.resetCity();
                    }
                }
            } else {
                this.selected = null;
                if (this.search === '') {
                    this.search = 'Search City';
                }
            }

            this.$watch('search', (value) => {
                if (value === '' && this.selected !== null) {
                    this.resetCity();
                }
            });
        }
    }"
    x-init="init()"
    @click.outside="closeDropdown()"
    class="select-container"
>
    <input
        type="hidden"
        name="{{ $attributes->get('name', 'origin_city_id') }}"
        x-model="selected"
        {{ $attributes->except(['name', 'city-selected-event']) }}
    >
    <input
        type="text"
        x-model="search"
        @input.debounce.300ms="searchCities()"
        @focus="open = true; selectAll($event)"
        @blur="setTimeout(() => { if (!$el.closest('[x-data]').querySelector('.select-dropdown:hover')) closeDropdown() }, 150)"
        placeholder="Search City"
        class="select-input"
        autocomplete="off"
    >
    <div x-show="open" x-transition="" class="select-dropdown">
        <div class="select-list">
            <template x-if="loading">
                <div class="select-info">Loading...</div>
            </template>
            <template x-if="!loading && search.length < 2">
                <div class="select-info">Type at least 2 characters to search for a city/province</div>
            </template>
            <template x-if="!loading && cities.length === 0 && search.length >= 2">
                <div class="select-info">No cities found matching your search</div>
            </template>
            <template x-for="city in cities" :key="city.id">
                <button
                    type="button"
                    @click="selectCity(city)"
                    class="select-item"
                    x-text="city.name"
                ></button>
            </template>
        </div>
    </div>
</div>
