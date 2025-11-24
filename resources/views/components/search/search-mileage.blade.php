{{-- resources/views/components/search/search-mileage.blade.php --}}
@props(['value' => null])
<?php
// Define common mileage tiers as raw numeric values
$mileageTiers = [
    10000,
    50000,
    100000,
    200000,
    300000,
];
?>

<div class="select-container w-full">
    {{-- 💡 CHANGE: Converted to a standard HTML select element --}}
    <select name="mileage" class="select-input">
        <option value="">Max Mileage</option>

        @foreach ($mileageTiers as $valueOption)
            <option
                value="{{ $valueOption }}"
                {{-- Use the value attribute passed via props for pre-selection --}}
                @selected($value == $valueOption)>
                {{-- Display the formatted mileage for the user --}}
                Under {{ number_format($valueOption) }} km
            </option>
        @endforeach
    </select>
</div>
