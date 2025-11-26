{{-- resources/views/components/search/search-range-slider.blade.php --}}
@props(['name' => 'range_km', 'maxRange' => 1000])

{{--
    Vanilla JS Component Wrapper
    Logic: resources/js/search/SearchRangeSlider.js
--}}
<div
    class="js-search-range-slider w-full relative z-20"
    data-name="{{ $name }}"
    data-max-range="{{ $maxRange }}"
>
    {{-- Hidden input keeps a stable ID for InstantSearch/Form Submit --}}
    <input type="hidden" name="{{ $name }}" id="{{ $name }}_filter" value="5">

    {{-- Header / Info --}}
    <div class="flex justify-between mb-2">
        <label class="text-sm font-medium text-gray-700">
            Max Distance
            {{-- Loader is hidden by default via CSS --}}
            <span class="range-loader text-xs text-gray-500 hidden" style="display: none;">(Calculating max...)</span>:
        </label>
        {{-- Display Value Span --}}
        <span class="range-value-display font-bold text-primary">5 km</span>
    </div>

    {{--
        Native Range Input
        Styled via .range-slider-input in app.css
    --}}
    <input
        type="range"
        min="5"
        max="{{ $maxRange }}"
        step="1"
        value="5"
        class="range-slider-input w-full"
    />

    {{-- Footer / Limits --}}
    <div class="flex justify-between text-xs text-gray-500 mt-1">
        <span>5 km</span>
        <span class="range-max-display">{{ round($maxRange) }} km</span>
    </div>
</div>
