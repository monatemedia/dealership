{{-- resources/views/components/search/search-province.blade.php --}}
@php
    // Allow dynamic event name via dispatch-event attribute
    $dispatchEvent = $attributes->get('dispatch-event', 'province-selected');
@endphp
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
        this.search = name;

        console.log('🗺️ Province selected:', { id, name });
        this.$dispatch('{{ $dispatchEvent }}', { id, name });
    },

    async init() {
        if (this.selected) {
            const response = await fetch(`/api/provinces/${this.selected}`);
            const data = await response.json();
            this.selectedName = data.name;
            this.search = data.name;
        }

        // Watch for manual clearing - only dispatch reset event
        this.$watch('search', (value) => {
            if (value === '' && this.selected !== null) {
                console.log('🗑️ Province input cleared');
                this.selected = null;
                this.selectedName = '';
                this.$dispatch('{{ $dispatchEvent }}', { id: null, name: '' });
            }
        });
    },

    closeDropdown() {
        this.open = false;
    }
}"
@click.outside="closeDropdown()"
class="select-container">
    <input type="hidden" name="{{ $attributes->get('name', 'province_id') }}" x-model="selected">
    <input
        type="text"
        x-model="search"
        @input.debounce.300ms="searchProvinces()"
        @focus="open = true"
        @blur="setTimeout(() => { if (!$el.closest('[x-data]').querySelector('.select-dropdown:hover')) closeDropdown() }, 150)"
        placeholder="Select Province"
        class="select-input"
        autocomplete="off"
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
            <template x-if="!loading && search.length < 1">
                <div class="select-info">Type to search provinces</div>
            </template>
            <template x-if="!loading && provinces.length === 0 && search.length >= 1">
                <div class="select-info">No provinces found</div>
            </template>
            <template x-for="province in provinces" :key="province.id">
                <button
                    type="button"
                    @click="selectProvince(province.id, province.name)"
                    class="select-item"
                    x-text="province.name"
                ></button>
            </template>
        </div>
    </div>
</div>
