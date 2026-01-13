<div x-data="{
    search: '',
    provinces: [],
    selected: @js($attributes->get('value')),
    selectedName: '',
    open: false,
    loading: false,
    cursor: -1,
    searchPromise: null,

    // Normalizer to help the snap find matches
    normalize(str) {
        if (!str) return '';
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLowerCase().trim();
    },

    async searchProvinces() {
        if (this.selected && this.search === this.selectedName) return;
        if (this.search.length < 1) {
            this.provinces = [];
            return;
        }

        this.loading = true;
        this.searchPromise = fetch(`/api/provinces/search?q=${encodeURIComponent(this.search)}`)
            .then(response => response.json())
            .then(data => {
                this.provinces = data;
                this.cursor = -1;
                if (document.activeElement !== this.$refs.provinceInput) this.open = false;
                return data;
            })
            .finally(() => { this.loading = false; });
        return this.searchPromise;
    },

    async autoResolve() {
        this.open = false;

        // Force search if user tabbed before debounce
        if (this.search.length >= 1 && (this.provinces.length === 0 || this.loading)) {
            await this.searchProvinces();
        }

        if (this.selected || this.provinces.length === 0) return;

        const normalizedSearch = this.normalize(this.search);
        const match = this.provinces.find(p => this.normalize(p.name) === normalizedSearch);

        if (match) {
            this.selectProvince(match.id, match.name);
        } else if (this.cursor >= 0) {
            this.selectProvince(this.provinces[this.cursor].id, this.provinces[this.cursor].name);
        } else if (this.provinces.length === 1) {
            this.selectProvince(this.provinces[0].id, this.provinces[0].name);
        } else {
            // Revert if no valid snap found
            this.search = this.selectedName;
        }
    },

    selectProvince(id, name) {
        this.selected = id;
        this.selectedName = name;
        this.search = name;
        this.open = false;
        this.$dispatch('province-selected', { id });
    },

    selectNext() { if (this.provinces.length > 0) this.cursor = (this.cursor + 1) % this.provinces.length; this.scrollToCursor(); },
    selectPrev() { if (this.provinces.length > 0) this.cursor = (this.cursor - 1 + this.provinces.length) % this.provinces.length; this.scrollToCursor(); },

    scrollToCursor() {
        this.$nextTick(() => {
            const el = this.$refs.list?.children[this.cursor];
            if (el) el.scrollIntoView({ block: 'nearest' });
        });
    },

    async init() {
        if (this.selected) {
            try {
                const response = await fetch(`/api/provinces/${this.selected}`);
                const data = await response.json();
                this.selectedName = data.name;
                this.search = data.name;
            } catch (e) { console.error(e); }
        }
    }
}" @click.away="open = false" class="select-container">
    <input type="hidden" name="province_id" :value="selected" tabindex="-1">
    <input
        x-ref="provinceInput"
        type="text"
        x-model="search"
        @input.debounce.300ms="searchProvinces(); selected = null; if(search.length > 0 && document.activeElement === $el) open = true;"
        @focus="if(!selected || search !== selectedName) open = true"
        @keydown.arrow-down.prevent="open = true; selectNext()"
        @keydown.arrow-up.prevent="open = true; selectPrev()"
        @keydown.enter.prevent="if(cursor >= 0) selectProvince(provinces[cursor].id, provinces[cursor].name)"
        @keydown.tab="autoResolve()"
        placeholder="Select Province"
        class="select-input"
        autocomplete="off"
    >
    <div x-show="open" x-transition class="select-dropdown" style="display: none;">
        <div class="select-list" x-ref="list">
            <template x-if="loading"><div class="select-info">Loading...</div></template>
            <template x-if="!loading && provinces.length === 0 && search.length >= 1">
                <div class="select-info">No provinces found</div>
            </template>
            <template x-for="(province, index) in provinces" :key="province.id">
                <button type="button" @click="selectProvince(province.id, province.name)" @mouseenter="cursor = index"
                    class="select-item" :class="{ 'is-active': cursor === index }" tabindex="-1" x-text="province.name"></button>
            </template>
        </div>
    </div>
</div>
