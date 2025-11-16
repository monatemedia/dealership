{{-- resources/views/vehicle/search.blade.php --}}
<x-app-layout title="Search">
    <main>
        <!-- Found Vehicles -->
        <section>
            <div class="container">
                <!-- Search Input (New) -->
                <div class="mb-medium">
                    <div class="find-a-vehicle-form card p-medium">
                        <div class="form-group">
                            <label class="mb-medium">Search Vehicles</label>
                            <input
                                type="text"
                                id="instant-search-input"
                                placeholder="Search by make, model, location, type..."
                                style="width: 100%;"
                            />
                            <small class="text-muted">Start typing to search instantly</small>
                        </div>
                    </div>
                </div>

                <div class="sm:flex items-center justify-between mb-medium">
                    <div class="flex items-center">
                        <button class="show-filters-button flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" style="width: 20px">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6 13.5V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 0 1 0 3m0-3a1.5 1.5 0 0 0 0 3m0 9.75V10.5" />
                            </svg>
                            Filters
                        </button>
                        <h2 id="search-results-count">Define your search criteria</h2>
                    </div>

                    <select class="sort-dropdown" id="sort-dropdown">
                        <option value="">Order By</option>
                        <option value="price">Price Asc</option>
                        <option value="-price">Price Desc</option>
                        <option value="year">Year Asc</option>
                        <option value="-year">Year Desc</option>
                        <option value="mileage">Mileage Asc</option>
                        <option value="-mileage">Mileage Desc</option>
                        <option value="published_at">Oldest Listing First</option>
                        <option value="-published_at">Latest Listing First</option>
                    </select>
                </div>

                <div class="search-vehicle-results-wrapper">
                    <div class="search-vehicles-sidebar">
                        <div class="card card-found-vehicles">
                            <p class="m-0">Found <strong id="total-results">0</strong> vehicles</p>

                            <button class="close-filters-button">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    style="width: 24px">
                                    <path fill-rule="evenodd"
                                        d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>

                        <!-- Find a vehicle form -->
                        <section class="find-a-vehicle">
                            <form id="filter-form" class="find-a-vehicle-form card flex p-medium">
                                <div class="find-a-vehicle-inputs">
                                    <div class="form-group">
                                        <label class="mb-medium">Manufacturer</label>
                                        <x-select-manufacturer name="manufacturer_id"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Model</label>
                                        <x-select-model name="model_id"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Type</label>
                                        <x-select-vehicle-type name="vehicle_type_id"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Year</label>
                                        <div class="flex gap-1">
                                            <input type="number" placeholder="Year From" name="year_from"/>
                                            <input type="number" placeholder="Year To" name="year_to"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Price</label>
                                        <div class="flex gap-1">
                                            <input type="number" placeholder="Price From" name="price_from"/>
                                            <input type="number" placeholder="Price To" name="price_to"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Mileage</label>
                                        <div class="flex gap-1">
                                            <x-select-mileage name="mileage"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Province</label>
                                        <x-select-province name="province_id"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">City</label>
                                        <x-select-city name="city_id"/>
                                    </div>
                                    <div class="form-group">
                                        <label class="mb-medium">Fuel Type</label>
                                        <x-select-fuel-type name="fuel_type_id"/>
                                    </div>
                                </div>
                                <div class="flex">
                                    <button type="button" class="btn btn-find-a-vehicle-reset" id="reset-filters">
                                        Reset
                                    </button>
                                    <button type="button" class="btn btn-primary btn-find-a-vehicle-submit" id="apply-filters">
                                        Search
                                    </button>
                                </div>
                            </form>
                        </section>
                        <!--/ Find a vehicle form -->
                    </div>

                    <div class="search-vehicles-results">
                        <!-- Loading indicator -->
                        <div id="loading-indicator" class="hidden text-center p-large">
                            <p>Searching...</p>
                        </div>

                        <!-- Results container -->
                        <div id="search-results" class="vehicle-items-listing">
                            <!-- Results will be inserted here by JavaScript -->
                        </div>

                        <!-- No results message -->
                        <div id="no-results" class="hidden text-center p-large">
                            No vehicles were found by given search criteria.
                        </div>

                        <!-- Pagination -->
                        <div id="pagination-container"></div>
                    </div>
                </div>
            </div>
        </section>
        <!--/ Found Vehicles -->
    </main>

    <!-- Move scripts to bottom of page, before closing body tag -->
    <script>
        console.log('Script is loading...'); // Debug to confirm script runs

        // Instant Search Implementation
        class VehicleInstantSearch {
            constructor() {
                console.log('Constructor called'); // Debug

                // Check if all required elements exist
                this.searchInput = document.getElementById('instant-search-input');
                this.filterForm = document.getElementById('filter-form');
                this.resultsContainer = document.getElementById('search-results');
                this.loadingIndicator = document.getElementById('loading-indicator');
                this.noResults = document.getElementById('no-results');
                this.totalResults = document.getElementById('total-results');
                this.searchResultsCount = document.getElementById('search-results-count');
                this.sortDropdown = document.getElementById('sort-dropdown');

                // Debug: Check if elements exist
                console.log('Elements found:', {
                    searchInput: !!this.searchInput,
                    filterForm: !!this.filterForm,
                    resultsContainer: !!this.resultsContainer,
                    loadingIndicator: !!this.loadingIndicator,
                    noResults: !!this.noResults,
                    totalResults: !!this.totalResults,
                    searchResultsCount: !!this.searchResultsCount,
                    sortDropdown: !!this.sortDropdown
                });

                // If critical elements don't exist, stop
                if (!this.searchInput || !this.resultsContainer) {
                    console.error('Critical elements missing!');
                    return;
                }

                this.searchTimeout = null;
                this.currentPage = 1;
                this.currentQuery = '';
                this.currentFilters = {};

                console.log('VehicleInstantSearch initialized');
                this.init();
            }

            init() {
                console.log('Initializing event listeners...');

                // Search input with debounce
                this.searchInput.addEventListener('input', (e) => {
                    clearTimeout(this.searchTimeout);
                    this.searchTimeout = setTimeout(() => {
                        this.currentPage = 1;
                        this.currentQuery = e.target.value;
                        this.performSearch();
                    }, 300);
                });

                // Filter form submission
                const applyBtn = document.getElementById('apply-filters');
                if (applyBtn) {
                    applyBtn.addEventListener('click', () => {
                        this.currentPage = 1;
                        this.updateFilters();
                        this.performSearch();
                    });
                }

                // Reset filters
                const resetBtn = document.getElementById('reset-filters');
                if (resetBtn) {
                    resetBtn.addEventListener('click', () => {
                        this.filterForm.reset();
                        this.currentFilters = {};
                        this.currentPage = 1;
                        this.performSearch();
                    });
                }

                // Sort dropdown
                if (this.sortDropdown) {
                    this.sortDropdown.addEventListener('change', () => {
                        this.currentPage = 1;
                        this.performSearch();
                    });
                }

                console.log('Event listeners attached, performing initial search...');

                // Initial load - THIS IS THE KEY LINE
                this.performSearch();
            }

            updateFilters() {
                const formData = new FormData(this.filterForm);
                this.currentFilters = {};

                for (let [key, value] of formData.entries()) {
                    if (value) {
                        this.currentFilters[key] = value;
                    }
                }
            }

            async performSearch() {
                this.showLoading();

                const params = new URLSearchParams({
                    q: this.currentQuery,
                    page: this.currentPage,
                    sort: this.sortDropdown.value,
                    ...this.currentFilters
                });

                console.log('Searching with params:', params.toString()); // Debug

                try {
                    const response = await fetch(`/api/vehicles/search?${params.toString()}`);
                    console.log('Response status:', response.status); // Debug

                    const data = await response.json();
                    console.log('Response data:', data); // Debug

                    this.renderResults(data);
                    this.updateStats(data);

                } catch (error) {
                    console.error('Search error:', error);
                    this.showError();
                } finally {
                    this.hideLoading();
                }
            }

            renderResults(data) {
                console.log('Rendering results:', data.hits?.length || 0, 'vehicles'); // Debug

                if (!data.hits || data.hits.length === 0) {
                    this.resultsContainer.innerHTML = '';
                    this.noResults.classList.remove('hidden');
                    console.log('No results to display'); // Debug
                    return;
                }

                this.noResults.classList.add('hidden');

                // Hits are already rendered HTML from Laravel
                console.log('First hit sample:', data.hits[0]?.substring(0, 100)); // Debug
                this.resultsContainer.innerHTML = data.hits.join('');
            }

            updateStats(data) {
                this.totalResults.textContent = data.nbHits || 0;

                const query = this.currentQuery;
                if (query) {
                    this.searchResultsCount.textContent = `Found ${data.nbHits} results for "${query}"`;
                } else {
                    this.searchResultsCount.textContent = `Found ${data.nbHits} vehicles`;
                }
            }

            showLoading() {
                this.loadingIndicator.classList.remove('hidden');
                this.resultsContainer.style.opacity = '0.5';
            }

            hideLoading() {
                this.loadingIndicator.classList.add('hidden');
                this.resultsContainer.style.opacity = '1';
            }

            showError() {
                this.resultsContainer.innerHTML = `
                    <div class="text-center p-large">
                        <p class="text-error">An error occurred while searching. Please try again.</p>
                    </div>
                `;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM Content Loaded, initializing search...'); // Debug
            new VehicleInstantSearch();
        });
    </script>
</x-app-layout>
