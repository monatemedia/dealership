{{-- resources/views/components/select-manufacturer.blade.php --}}
<div x-data="{
    search: '',
    manufacturers: [],
    selected: @js($attributes->get('value')),
    selectedName: '',
    open: false,
    loading: false,

    async searchManufacturers() {
        if (this.search.length < 2) {
            this.manufacturers = [];
            return;
        }

        this.loading = true;
        try {
            const response = await fetch(`{{ route('api.manufacturers.search') }}?q=${encodeURIComponent(this.search)}`);
            this.manufacturers = await response.json();
        } catch (error) {
            console.error('Error fetching manufacturers:', error);
        }
        this.loading = false;
    },

    selectManufacturer(id, name) {
        this.selected = id;
        this.selectedName = name;
        this.open = false;
        this.search = '';
        this.$dispatch('manufacturer-selected', { id });
    },

    async init() {
        if (this.selected) {
            const response = await fetch(`/api/manufacturers/${this.selected}`);
            const data = await response.json();
            this.selectedName = data.name;
        }
    }
}"
@click.away="open = false"
class="relative">

    <input type="hidden" name="manufacturer_id" x-model="selected">

    <div class="relative">
        <button
            type="button"
            @click="open = !open"
            class="w-full text-left border rounded px-3 py-2 bg-white"
        >
            <span x-text="selectedName || 'Select Manufacturer'"></span>
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
                    @input.debounce.300ms="searchManufacturers()"
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

                <template x-if="!loading && manufacturers.length === 0 && search.length >= 2">
                    <div class="px-3 py-2 text-gray-500">No manufacturers found</div>
                </template>

                <template x-for="manufacturer in manufacturers" :key="manufacturer.id">
                    <button
                        type="button"
                        @click="selectManufacturer(manufacturer.id, manufacturer.name)"
                        class="w-full text-left px-3 py-2 hover:bg-gray-100"
                        x-text="manufacturer.name"
                    ></button>
                </template>
            </div>
        </div>
    </div>
</div>
