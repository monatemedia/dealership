{{-- resources/views/components/drive-train-selector.blade.php --}}
@props(['driveTrains', 'defaultDriveTrain' => null, 'value' => null])

@php
    $groupedDriveTrains = [];

    $selectedId = $value ?? $driveTrains->firstWhere('name', $defaultDriveTrain)?->id;
    $selectedName = $driveTrains->firstWhere('id', $selectedId)?->name ?? 'Select Drive Train';

    foreach($driveTrains as $item) {
        $groupName = $item->driveTrainGroup->name ?? 'Other';
        if (!isset($groupedDriveTrains[$groupName])) {
            $groupedDriveTrains[$groupName] = [];
        }
        $groupedDriveTrains[$groupName][] = [
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

    <input type="hidden" name="drive_train_id" x-model="selectedId" />
    <div
         @click="openModal"
         class="fuel-type-input"
        :class="{ 'has-selection': selectedId !== null && selectedId !== '' }"
    >
        <span x-text="selectedName" class="fuel-type-display"></span>
        <i class="fa-solid fa-chevron-down"></i>
    </div>

    <x-modal-overlay title="Select Drive Train" max-width="600px">
        <p class="modal-subtitle">Choose the drive train for your vehicle</p>

        <x-grouped-radio-list
             :groups="$groupedDriveTrains"
            name="drive_train_modal"
            :selected="$selectedId"
        />
    </x-modal-overlay>
</div>
