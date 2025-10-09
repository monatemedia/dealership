{{-- resources/views/components/select-city.blade.php --}}
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
    }
}"
@province-selected.window="provinceId = $event.detail.id; selected = null; selectedName = ''; search = ''"
@click.away="open = false"
class="select-container">
    <input type="hidden" name="city_id" x-model="selected">
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
