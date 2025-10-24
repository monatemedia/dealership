{{-- resources/views/components/drive-train-selector.blade.php --}}
@props(['driveTrains', 'defaultDrivetrain' => null, 'value' => null])

@php
    $groupedDrivetrains = [];

    $selectedId = $value ?? $driveTrains->firstWhere('name', $defaultDrivetrain)?->id;
    $selectedName = $driveTrains->firstWhere('id', $selectedId)?->name ?? 'Select Drivetrain';

    foreach($driveTrains as $item) {
        $groupName = $item->driveTrainGroup->name ?? 'Other';
        if (!isset($groupedDrivetrains[$groupName])) {
            $groupedDrivetrains[$groupName] = [];
        }
        $groupedDrivetrains[$groupName][] = [
            'value' => $item->id,
            'label' => $item->name
        ];
    }
@endphp

<div
     x-data="itemSelector({
        selectedId: '{{ $selectedId }}',
        selectedName: '{{ $selectedName }}'
    })"
    class="fuel-type-selector"> {{-- Re-use same class for styling --}}

    <input type="hidden" name="drivetrain_id" x-model="selectedId" />
    <div
         @click="openModal"
         class="fuel-type-input"
        :class="{ 'has-selection': selectedId !== null && selectedId !== '' }"
    >
        <span x-text="selectedName" class="fuel-type-display"></span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>

    <x-modal-overlay title="Select Drivetrain" max-width="600px">
        <p class="modal-subtitle">Choose the drive train for your vehicle</p>

        <x-grouped-radio-list
             :groups="$groupedDrivetrains"
            name="drivetrain_modal"
            :selected="$selectedId"
        />
    </x-modal-overlay>
</div>
