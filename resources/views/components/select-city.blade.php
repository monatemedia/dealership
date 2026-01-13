<div x-data="{
    search: '',
    cities: [],
    selected: @js($attributes->get('value')),
    selectedName: '',
    provinceId: null,
    open: false,
    loading: false,
    cursor: -1,
    searchPromise: null,

    normalize(str) {
        if (!str) return '';
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
    },

    async searchCities(overrideProvinceId = null) {
        if (this.search.length < 2) {
            this.cities = [];
            return;
        }

        this.loading = true;

        // Use the override if provided (from the event), otherwise use the component state
        const pid = overrideProvinceId !== null ? overrideProvinceId : this.provinceId;

        let url = `/api/cities/search?q=${encodeURIComponent(this.search)}`;
        if (pid) {
            url += `&province_id=${pid}`;
        }

        this.searchPromise = fetch(url)
            .then(response => response.json())
            .then(data => {
                this.cities = data;
                this.cursor = -1;

                // Re-open the dropdown if the user is currently focused here
                // and we just got new constrained results
                if (document.activeElement === this.$refs.cityInput && this.cities.length > 0) {
                    this.open = true;
                }

                return data;
            })
            .catch(error => console.error('Error fetching cities:', error))
            .finally(() => { this.loading = false; });

        return this.searchPromise;
    },

    async autoResolve() {
        this.open = false;

        // Force search if user tabbed before debounce or during load
        if (this.search.length >= 2 && (this.cities.length === 0 || this.loading)) {
            await this.searchCities();
        }

        if (this.selected || this.cities.length === 0) return;

        const normalizedSearch = this.normalize(this.search);
        const match = this.cities.find(c => this.normalize(c.name) === normalizedSearch);

        if (match) {
            this.selectCity(match.id, match.name);
        } else if (this.cursor >= 0) {
            this.selectCity(this.cities[this.cursor].id, this.cities[this.cursor].name);
        } else if (this.cities.length === 1) {
            this.selectCity(this.cities[0].id, this.cities[0].name);
        } else {
            // Strict selection: revert if no match found
            this.search = this.selectedName;
        }
    },

    selectCity(id, name) {
        this.selected = id;
        this.selectedName = name;
        this.search = name;
        this.open = false;
        this.cursor = -1;
    },

    selectNext() { if (this.cities.length > 0) this.cursor = (this.cursor + 1) % this.cities.length; this.scrollToCursor(); },
    selectPrev() { if (this.cities.length > 0) this.cursor = (this.cursor - 1 + this.cities.length) % this.cities.length; this.scrollToCursor(); },

    scrollToCursor() {
        this.$nextTick(() => {
            const el = this.$refs.list?.children[this.cursor];
            if (el) el.scrollIntoView({ block: 'nearest' });
        });
    },

    async init() {
        if (this.selected) {
            try {
                const response = await fetch(`/api/cities/${this.selected}`);
                const data = await response.json();
                this.selectedName = data.name;
                this.search = data.name;
                this.provinceId = data.province_id;
            } catch (e) { console.error(e); }
        }
    }
}"
{{-- RESET LOGIC: When a province is selected, clear the city completely --}}
@province-selected.window="
    provinceId = $event.detail.id;
    selected = null;
    selectedName = '';
    if (search.length >= 2) {
        // Pass the ID directly to ensure searchCities uses the fresh value
        searchCities($event.detail.id);
    } else {
        search = '';
    }
"
@click.away="open = false"
class="select-container">

    <input type="hidden" name="city_id" :value="selected" tabindex="-1">

    <input
        x-ref="cityInput"
        type="text"
        x-model="search"
        @input.debounce.300ms="searchCities(); selected = null; if(search.length > 0 && document.activeElement === $el) open = true;"
        @focus="if(!selected || search !== selectedName) open = true"
        @keydown.arrow-down.prevent="open = true; selectNext()"
        @keydown.arrow-up.prevent="open = true; selectPrev()"
        @keydown.enter.prevent="if(cursor >= 0) selectCity(cities[cursor].id, cities[cursor].name)"
        @keydown.tab="autoResolve()"
        placeholder="Select City"
        class="select-input"
        autocomplete="off"
    >

    <div x-show="open" x-transition class="select-dropdown" style="display: none;">
        <div class="select-list" x-ref="list">
            <template x-if="loading"><div class="select-info">Searching...</div></template>

            <template x-if="!loading && cities.length === 0 && search.length >= 2">
                <div class="select-info">No cities found.</div>
            </template>

            <template x-for="(city, index) in cities" :key="city.id">
                <button
                    type="button"
                    @click="selectCity(city.id, city.name)"
                    @mouseenter="cursor = index"
                    class="select-item"
                    :class="{ 'is-active': cursor === index }"
                    tabindex="-1"
                    x-text="city.name"
                ></button>
            </template>
        </div>
    </div>
</div>
