{{-- resources/views/components/fuel-type-selector.blade.php --}}
@props(['fuelTypes', 'defaultFuelType' => null, 'value' => null])

@php
    // Determine selected fuel type
    $selectedId = $value ?? $fuelTypes->firstWhere('name', $defaultFuelType)?->id;
    $selectedName = $fuelTypes->firstWhere('id', $selectedId)?->name ?? 'Select Fuel Type';
@endphp

<div
    x-data="fuelTypeSelector({
        fuelTypes: {{ $fuelTypes->toJson() }},
        selectedId: {{ $selectedId ? "'{$selectedId}'" : 'null' }},
        selectedName: '{{ $selectedName }}'
    })"
    class="fuel-type-selector"
>
    {{-- Hidden input to store the actual value --}}
    <input type="hidden" name="fuel_type_id" x-model="selectedId" />

    {{-- Clickable display input --}}
    <div
        @click="openModal"
        class="fuel-type-input"
        :class="{ 'has-value': selectedId }"
    >
        <span x-text="selectedName"></span>
        <svg class="chevron-icon" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M5 7.5L10 12.5L15 7.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </div>

    {{-- Modal overlay --}}
    <div
        x-show="isOpen"
        x-cloak
        class="fuel-modal-backdrop"
        @click="closeModal"
    >
        <div
            class="fuel-modal-content"
            @click.stop
        >
            <div class="fuel-modal-header">
                <h3>Select Fuel Type</h3>
                <button type="button" @click="closeModal" class="close-button">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </button>
            </div>

            <div class="fuel-modal-body">
                <template x-for="fuelType in fuelTypes" :key="fuelType.id">
                    <label class="fuel-option">
                        <input
                            type="radio"
                            :value="fuelType.id"
                            x-model="selectedId"
                            @change="selectFuelType(fuelType)"
                        />
                        <span class="fuel-option-label" x-text="fuelType.name"></span>
                        <span class="checkmark" x-show="selectedId == fuelType.id">✓</span>
                    </label>
                </template>
            </div>

            <div class="fuel-modal-footer">
                <button type="button" @click="closeModal" class="btn-secondary">
                    Cancel
                </button>
                <button type="button" @click="confirmSelection" class="btn-primary">
                    Confirm
                </button>
            </div>
        </div>
    </div>
</div>
