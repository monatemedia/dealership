<div x-data="{
    search: @js($attributes->get('search', '')),
    models: [],
    selected: @js($attributes->get('value')),
    selectedName: '',
    manufacturerId: null,
    open: false,
    loading: false,
    cursor: -1,
    searchPromise: null,

    normalize(str) {
        if (!str) return '';
        return str.normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .replace(/&/g, 'and')
            .replace(/[\s\.\-\/]/g, '')
            .trim();
    },

    shouldOpen() {
        if (this.selected && this.search === this.selectedName) return false;
        return true;
    },

    async searchModels() {
        if (!this.manufacturerId || this.search.length < 2) {
            this.models = [];
            return;
        }

        this.loading = true;
        this.searchPromise = fetch(`/api/models/search?q=${encodeURIComponent(this.search)}&manufacturer_id=${this.manufacturerId}`)
            .then(response => response.json())
            .then(data => {
                this.models = data;
                this.cursor = -1;
                // Focus Guard: If user tabbed away, keep it closed
                if (document.activeElement !== this.$refs.modelInput) {
                    this.open = false;
                }
                return data;
            })
            .catch(error => console.error('Error fetching models:', error))
            .finally(() => { this.loading = false; });

        return this.searchPromise;
    },

    async autoResolve() {
        this.open = false;

        // Force search if we are tabbing away but the debounce hasn't fired
        if (this.manufacturerId && this.search.length >= 2 && (this.models.length === 0 || this.loading)) {
            await this.searchModels();
        }

        if (this.loading && this.searchPromise) {
            await this.searchPromise;
        }

        if (this.selected || this.models.length === 0) return;

        const normalizedSearch = this.normalize(this.search);
        const match = this.models.find(m => this.normalize(m.name) === normalizedSearch);

        if (match) {
            this.selectModel(match.id, match.name);
        } else if (this.cursor >= 0) {
            this.selectCurrent();
        } else if (this.models.length === 1) {
            this.selectModel(this.models[0].id, this.models[0].name);
        }
    },

    selectModel(id, name) {
        this.selected = id;
        this.selectedName = name;
        this.search = name;
        this.open = false;
        this.cursor = -1;
    },

    selectNext() {
        if (this.models.length > 0) {
            this.cursor = (this.cursor + 1) % this.models.length;
            this.scrollToCursor();
        }
    },

    selectPrev() {
        if (this.models.length > 0) {
            this.cursor = (this.cursor - 1 + this.models.length) % this.models.length;
            this.scrollToCursor();
        }
    },

    selectCurrent() {
        if (this.cursor >= 0 && this.models[this.cursor]) {
            this.selectModel(this.models[this.cursor].id, this.models[this.cursor].name);
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
                const response = await fetch(`/api/models/${this.selected}`);
                const data = await response.json();
                this.selectedName = data.name;
                this.search = data.name;
                this.manufacturerId = data.manufacturer_id;
            } catch (error) {
                console.error('Error fetching initial model:', error);
            }
        }
    }
}"
@manufacturer-selected.window="manufacturerId = $event.detail.id; selected = null; selectedName = ''; search = ''; open = false;"
@click.away="open = false"
class="select-container">

    <input type="hidden" name="model_id" :value="selected ? selected : search" tabindex="-1">

    <input
        x-ref="modelInput"
        type="text"
        x-model="search"
        @input.debounce.300ms="searchModels(); selected = null; if(search.length > 0 && document.activeElement === $el) open = true;"
        @focus="if(shouldOpen()) open = true"
        @keydown.arrow-down.prevent="open = true; selectNext()"
        @keydown.arrow-up.prevent="open = true; selectPrev()"
        @keydown.enter.prevent="selectCurrent()"
        @keydown.tab="autoResolve()"
        @keydown.escape="open = false"
        placeholder="Select or Type Model"
        class="select-input"
        autocomplete="off"
    >

    <div x-show="open" x-transition class="select-dropdown" style="display: none;">
        <div class="select-list" x-ref="list">
            <template x-if="!manufacturerId && search.length > 0">
                <div class="select-info">Custom manufacturer detected.</div>
            </template>

            <template x-if="manufacturerId">
                <div>
                    <template x-if="loading">
                        <div class="select-info">Searching...</div>
                    </template>
                    <template x-if="!loading && search.length < 2">
                        <div class="select-info">Type at least 2 characters to search</div>
                    </template>
                    <template x-if="!loading && models.length === 0 && search.length >= 2">
                        <div class="select-info">No models found for this manufacturer.</div>
                    </template>
                    <template x-for="(model, index) in models" :key="model.id">
                        <button
                            type="button"
                            @click="selectModel(model.id, model.name)"
                            @mouseenter="cursor = index"
                            class="select-item"
                            :class="{ 'is-active': cursor === index }"
                            tabindex="-1"
                            x-text="model.name"
                        ></button>
                    </template>
                </div>
            </template>
        </div>
    </div>
</div>
