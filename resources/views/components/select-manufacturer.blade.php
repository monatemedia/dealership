{{-- resources/views/components/select-manufacturer.blade.php --}}
<div x-data="{
    search: '',
    manufacturers: [],
    selected: @js($attributes->get('value')),
    selectedName: '',
    open: false,
    loading: false,

    async searchManufacturers() {
        if (this.search === this.selectedName) {
            this.manufacturers = [];
            return;
        }

        if (this.search.length < 2) {
            this.manufacturers = [];
            return;
        }

        this.loading = true;
        try {
            const response = await fetch(`/api/manufacturers/search?q=${encodeURIComponent(this.search)}`);
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
        this.search = name;
        this.$dispatch('manufacturer-selected', { id });
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
class="select-container">
    <input type="hidden" name="manufacturer_id" x-model="selected">
    <input
        type="text"
        x-model="search"
        @input.debounce.300ms="searchManufacturers()"
        @focus="open = true"
        placeholder="Select Manufacturer"
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
            <template x-if="!loading && search.length < 2 && search !== selectedName">
                <div class="select-info">Type at least 2 characters to search</div>
            </template>
            <template x-if="!loading && manufacturers.length === 0 && search.length >= 2">
                <div class="select-info">No manufacturers found</div>
            </template>
            <template x-for="manufacturer in manufacturers" :key="manufacturer.id">
                <button
                    type="button"
                    @click="selectManufacturer(manufacturer.id, manufacturer.name)"
                    class="select-item"
                    x-text="manufacturer.name"
                ></button>
            </template>
        </div>
    </div>
</div>
