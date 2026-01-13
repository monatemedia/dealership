<div x-data="{
    search: @js($attributes->get('search', '')),
    manufacturers: [],
    selected: @js($attributes->get('value')),
    selectedName: '',
    open: false,
    loading: false,
    cursor: -1,
    searchPromise: null, // Track the active fetch request

    normalize(str) {
        if (!str) return '';
        return str.normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .replace(/&/g, 'and')
            .replace(/[\s\.\-\/]/g, '')
            .trim();
    },

    async searchManufacturers() {
        // Basic guards
        if (this.selected && this.search === this.selectedName) return;
        if (this.search.length < 2) return;

        this.loading = true;

        // Create the promise
        this.searchPromise = fetch(`/api/manufacturers/search?q=${encodeURIComponent(this.search)}`)
            .then(response => response.json())
            .then(data => {
                this.manufacturers = data;
                this.cursor = -1;
                // Only show dropdown if we still have focus
                if (document.activeElement !== this.$refs.searchInput) {
                    this.open = false;
                }
                return data;
            })
            .finally(() => { this.loading = false; });

        return this.searchPromise;
    },

    async autoResolve() {
        // 1. If we already have a selection, just close and stop.
        if (this.selected) {
            this.open = false;
            return;
        }

        // 2. We are tabbing away. Close dropdown immediately to stop 'ghosts'.
        this.open = false;

        // 3. FORCE SEARCH: If manufacturers list is empty but we have text,
        // run searchManufacturers immediately and WAIT for it.
        if (this.search.length >= 2 && (this.manufacturers.length === 0 || this.loading)) {
            await this.searchManufacturers();
        }

        // 4. Double check we have results now
        if (!this.manufacturers || this.manufacturers.length === 0) return;

        // 5. Normalization Match
        const normalizedSearch = this.normalize(this.search);
        const match = this.manufacturers.find(m => this.normalize(m.name) === normalizedSearch);

        if (match) {
            this.selectManufacturer(match.id, match.name);
        }
        // If only one result exists (and it's not a match), snap to it anyway?
        // Let's keep it strict to normalized matches or user-highlighted items.
        else if (this.cursor >= 0) {
            this.selectCurrent();
        }
    },

    selectManufacturer(id, name) {
        this.selected = id;
        this.selectedName = name;
        this.search = name;
        this.open = false;
        this.$dispatch('manufacturer-selected', { id: id });
    },

    selectNext() {
        if (this.manufacturers.length > 0) {
            this.cursor = (this.cursor + 1) % this.manufacturers.length;
            this.scrollToCursor();
        }
    },
    selectPrev() {
        if (this.manufacturers.length > 0) {
            this.cursor = (this.cursor - 1 + this.manufacturers.length) % this.manufacturers.length;
            this.scrollToCursor();
        }
    },
    selectCurrent() {
        if (this.cursor >= 0 && this.manufacturers[this.cursor]) {
            this.selectManufacturer(this.manufacturers[this.cursor].id, this.manufacturers[this.cursor].name);
        } else {
            this.open = false;
        }
    },
    scrollToCursor() {
        this.$nextTick(() => {
            const el = this.$refs.list?.children[this.cursor];
            if (el) el.scrollIntoView({ block: 'nearest' });
        });
    },

    async init() {
        if (this.selected) {
            try {
                const response = await fetch(`/api/manufacturers/${this.selected}`);
                const data = await response.json();
                this.selectedName = data.name;
                this.search = data.name;
            } catch (error) {
                console.error('Error fetching initial manufacturer:', error);
            }
        }
    }
}"
@click.away="open = false"
class="select-container"
>
    <input type="hidden" name="manufacturer_id" :value="selected ? selected : search" tabindex="-1">

    <input
        x-ref="searchInput"
        type="text"
        x-model="search"
        @input.debounce.300ms="searchManufacturers(); selected = null; $dispatch('manufacturer-selected', { id: null }); if(search.length > 0) open = true;"
        @focus="if(shouldOpen()) open = true"
        @keydown.arrow-down.prevent="open = true; selectNext()"
        @keydown.arrow-up.prevent="open = true; selectPrev()"
        @keydown.enter.prevent="selectCurrent()"
        @keydown.escape="open = false"
        {{-- FIX 2: autoResolve handles selection, but we DON'T use .prevent so TAB still moves focus --}}
        @keydown.tab="autoResolve()"
        placeholder="Select Manufacturer"
        class="select-input"
        autocomplete="off"
    >

    <div x-show="open" x-transition class="select-dropdown" style="display: none;">
        <div class="select-list" x-ref="list">
            <template x-if="loading">
                <div class="select-info">Searching...</div>
            </template>
            <template x-if="!loading && search.length < 2">
                <div class="select-info">Type at least 2 characters to search</div>
            </template>
            <template x-if="!loading && manufacturers.length === 0 && search.length >= 2">
                <div class="select-info">No manufacturers found.</div>
            </template>
            <template x-for="(manufacturer, index) in manufacturers" :key="manufacturer.id">
                <button
                    type="button"
                    @click="selectManufacturer(manufacturer.id, manufacturer.name)"
                    @mouseenter="cursor = index"
                    class="select-item"
                    :class="{ 'is-active': cursor === index }"
                    tabindex="-1"
                    x-text="manufacturer.name"
                ></button>
            </template>
        </div>
    </div>
</div>
