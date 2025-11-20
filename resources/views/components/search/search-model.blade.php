{{-- resources/views/components/search/search-model.blade.php --}}
<div x-data="{
    search: '',
    models: [],
    selected: @js($attributes->get('value')),
    selectedName: '',
    manufacturerId: null,
    open: false,
    loading: false,

    async searchModels() {
        if (this.search.length < 2) {
            this.models = [];
            return;
        }

        this.loading = true;
        try {
            let url = `/api/models/search?q=${encodeURIComponent(this.search)}`;
            if (this.manufacturerId) {
                url += `&manufacturer_id=${this.manufacturerId}`;
            }
            const response = await fetch(url);
            this.models = await response.json();
        } catch (error) {
            console.error('Error fetching models:', error);
        }
        this.loading = false;
    },

    selectModel(id, name) {
        this.selected = id;
        this.selectedName = name;
        this.open = false;
        this.search = name;
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
@manufacturer-selected.window="manufacturerId = $event.detail.id; selected = null; selectedName = ''; search = ''"
@click.away="open = false"
class="select-container">
    <input type="hidden" name="model_id" x-model="selected">
    <input
        type="text"
        x-model="search"
        @input.debounce.300ms="searchModels()"
        @focus="open = true"
        placeholder="Select Model"
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
            <template x-if="!loading && models.length === 0 && search.length >= 2">
                <div class="select-info">No models found</div>
            </template>
            <template x-for="model in models" :key="model.id">
                <button
                    type="button"
                    @click="selectModel(model.id, model.name)"
                    class="select-item"
                    x-text="model.name"
                ></button>
            </template>
        </div>
    </div>
</div>
