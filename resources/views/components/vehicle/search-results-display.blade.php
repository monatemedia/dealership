<section>
    <div class="container">
        {{-- Header --}}
        <div class="section-header mb-medium">
            <h2 class="section-title">Latest Vehicles Added</h2>
            <span class="m-0">Found <strong id="total-results">0</strong> vehicles</span>
        </div>

        {{-- Main Results Grid --}}
        <div id="search-results" class="vehicle-grid">
            {{-- JS will inject items here --}}
        </div>

        {{-- Main Loading Indicator (Initial Load) --}}
        <div id="loading-indicator" class="loader-container hidden">
            <div class="loader main">
                <div class="ball"></div><div class="ball"></div><div class="ball"></div><div class="ball"></div>
            </div>
        </div>

        {{-- No Results Message --}}
        <div id="no-results" class="status-container hidden">
            <p class="no-results-text">No vehicles found.</p>
        </div>

        {{-- Load More Indicator (Infinite Scroll) --}}
        <div id="load-more-indicator" class="loader-container hidden" style="height: 80px;">
            <div class="loader main">
                <div class="ball"></div><div class="ball"></div><div class="ball"></div><div class="ball"></div>
            </div>
        </div>

        {{-- End of Results --}}
        <div id="end-of-results" class="end-message hidden">
            You've reached the end of the list.
        </div>
    </div>
</section>
