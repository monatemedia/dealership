{{-- resources/views/components/vehicle/search-results-list.blade.php --}}
<div class="search-vehicles-results" x-data="{ endReached: false }">
    <div id="loading-indicator" class="hidden" style="position: relative; min-height: 100px;">
        <div class="loader main">
            <div class="ball"></div><div class="ball"></div><div class="ball"></div><div class="ball"></div>
        </div>
    </div>

    <div id="search-results" class="vehicle-items-listing"></div>

    <div id="no-results" class="hidden text-center p-large">
        No vehicles found.
    </div>

    <div id="end-of-results" class="hidden text-center p-medium" style="color: var(--text-muted-color);">
        <p>You've reached the end of the results</p>
        <p style="font-size: 0.9rem;">Showing all <span id="total-shown">0</span> vehicles</p>
    </div>
</div>
