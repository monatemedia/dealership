{{-- resources/views/components/select-province.blade.php --}}
<div x-data="{
    search: '',
    provinces: [],
    selected: @js($attributes->get('value')),
    selectedName: '',
    open: false,
    loading: false,

    async searchProvinces() {
        if (this.search.length < 1) {
            this.provinces = [];
            return;
        }

        this.loading = true;
        try {
            const response = await fetch(`/api/provinces/search?q=${encodeURIComponent(this.search)}`);
            this.provinces = await response.json();
        } catch (error) {
            console.error('Error fetching provinces:', error);
        }
        this.loading = false;
    },

    selectProvince(id, name) {
        this.selected = id;
        this.selectedName = name;
        this.open = false;
        this.search = '';
        this.$dispatch('province-selected', { id });
    },

    async init() {
        if (this.selected) {
            const response = await fetch(`/api/provinces/${this.selected}`);
            const data = await response.json();
            this.selectedName = data.name;
        }
    }
}"
@click.away="open = false"
class="relative">

    <input type="hidden" name="province_id" x-model="selected">

    <div class="relative">
        <button
            type="button"
            @click="open = !open"
            class="w-full text-left border rounded px-3 py-2 bg-white"
        >
            <span x-text="selectedName || 'Select Province'"></span>
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
                    @input.debounce.300ms="searchProvinces()"
                    placeholder="Type to search..."
                    class="w-full border rounded px-3 py-2"
                >
            </div>

            <div class="max-h-60 overflow-y-auto">
                <template x-if="loading">
                    <div class="px-3 py-2 text-gray-500">Loading...</div>
                </template>

                <template x-if="!loading && search.length < 1">
                    <div class="px-3 py-2 text-gray-500">Type to search provinces</div>
                </template>

                <template x-if="!loading && provinces.length === 0 && search.length >= 1">
                    <div class="px-3 py-2 text-gray-500">No provinces found</div>
                </template>

                <template x-for="province in provinces" :key="province.id">
                    <button
                        type="button"
                        @click="selectProvince(province.id, province.name)"
                        class="w-full text-left px-3 py-2 hover:bg-gray-100"
                        x-text="province.name"
                    ></button>
                </template>
            </div>
        </div>
    </div>
</div>
