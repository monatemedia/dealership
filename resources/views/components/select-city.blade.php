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
        this.search = '';
    },

    async init() {
        if (this.selected) {
            const response = await fetch(`/api/cities/${this.selected}`);
            const data = await response.json();
            this.selectedName = data.name;
        }
    }
}"
@province-selected.window="provinceId = $event.detail.id; selected = null; selectedName = ''"
@click.away="open = false"
class="relative">

    <input type="hidden" name="city_id" x-model="selected">

    <div class="relative">
        <button
            type="button"
            @click="open = !open"
            class="w-full text-left border rounded px-3 py-2 bg-white"
        >
            <span x-text="selectedName || 'Select City'"></span>
        </button>

        <div
            x-show="open"
            x-transition
            class="absolute z-50 w-full mt-1 bg-white border rounded shadow-lg"
        >
            <div class="p-2">
                <input
                    type="text"
                    x-model="search"
                    @input.debounce.300ms="searchCities()"
                    placeholder="Type to search..."
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="max-h-60 overflow-y-auto">
                <template x-if="loading">
                    <div class="px-3 py-2 text-gray-500">Loading...</div>
                </template>

                <template x-if="!loading && search.length < 2">
                    <div class="px-3 py-2 text-gray-500">Type at least 2 characters to search</div>
                </template>

                <template x-if="!loading && cities.length === 0 && search.length >= 2">
                    <div class="px-3 py-2 text-gray-500">No cities found</div>
                </template>

                <template x-for="city in cities" :key="city.id">
                    <button
                        type="button"
                        @click="selectCity(city.id, city.name)"
                        class="w-full text-left px-3 py-2 hover:bg-gray-100"
                        x-text="city.name"
                    ></button>
                </template>
            </div>
        </div>
    </div>
</div>
