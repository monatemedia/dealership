{{-- resources/views/components/select-model.blade.php --}}
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
        this.search = '';
    },

    async init() {
        if (this.selected) {
            const response = await fetch(`/api/models/${this.selected}`);
            const data = await response.json();
            this.selectedName = data.name;
        }
    }
}"
@manufacturer-selected.window="manufacturerId = $event.detail.id; selected = null; selectedName = ''"
@click.away="open = false"
class="relative">

    <input type="hidden" name="model_id" x-model="selected">

    <div class="relative">
        <button
            type="button"
            @click="open = !open"
            class="w-full text-left border rounded px-3 py-2 bg-white"
        >
            <span x-text="selectedName || 'Select Model'"></span>
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
                    @input.debounce.300ms="searchModels()"
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

                <template x-if="!loading && models.length === 0 && search.length >= 2">
                    <div class="px-3 py-2 text-gray-500">No models found</div>
                </template>

                <template x-for="model in models" :key="model.id">
                    <button
                        type="button"
                        @click="selectModel(model.id, model.name)"
                        class="w-full text-left px-3 py-2 hover:bg-gray-100"
                        x-text="model.name"
                    ></button>
                </template>
            </div>
        </div>
    </div>
</div>
