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
                        <div id="loading-indicator" class="hidden" style="position: relative; min-height: 100px;">
                            <div class="loader main">
                                <div class="ball"></div>
                                <div class="ball"></div>
                                <div class="ball"></div>
                                <div class="ball"></div>
                            </div>
                        </div>

                        <!-- Results container -->
                        <div id="search-results" class="vehicle-items-listing">
                            <!-- Results will be inserted here by JavaScript -->
                        </div>

                        <!-- No results message -->
                        <div id="no-results" class="hidden text-center p-large">
                            No vehicles were found by given search criteria.
                        </div>

                        <!-- Load More Indicator (shown while loading more) -->
                        <div id="load-more-indicator" class="hidden" style="position: relative; height: 80px; margin: 2rem 0;">
                            <div class="loader main">
                                <div class="ball"></div>
                                <div class="ball"></div>
                                <div class="ball"></div>
                                <div class="ball"></div>
                            </div>
                        </div>

                        <!-- End of results message -->
                        <div id="end-of-results" class="hidden text-center p-medium" style="color: var(--text-muted-color);">
                            <p>You've reached the end of the results</p>
                            <p style="font-size: 0.9rem;">Showing all <span id="total-shown">0</span> vehicles</p>
                        </div>
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
                this.totalResultsEl = document.getElementById('total-results'); // Changed from totalResults
                this.searchResultsCount = document.getElementById('search-results-count');
                this.sortDropdown = document.getElementById('sort-dropdown');

                // Debug: Check if elements exist
                console.log('Elements found:', {
                    searchInput: !!this.searchInput,
                    filterForm: !!this.filterForm,
                    resultsContainer: !!this.resultsContainer,
                    loadingIndicator: !!this.loadingIndicator,
                    noResults: !!this.noResults,
                    totalResultsEl: !!this.totalResultsEl,
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
                this.isLoading = false;
                this.hasMoreResults = true;
                this.totalResultsCount = 0; // Changed variable name to avoid conflict

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
                        this.hasMoreResults = true;
                        this.performSearch(true);
                    }, 300);
                });

                // Filter form submission
                const applyBtn = document.getElementById('apply-filters');
                if (applyBtn) {
                    applyBtn.addEventListener('click', () => {
                        this.currentPage = 1;
                        this.hasMoreResults = true;
                        this.updateFilters();
                        this.performSearch(true);
                    });
                }

                // Reset filters
                const resetBtn = document.getElementById('reset-filters');
                if (resetBtn) {
                    resetBtn.addEventListener('click', () => {
                        this.filterForm.reset();
                        this.currentFilters = {};
                        this.currentPage = 1;
                        this.hasMoreResults = true;
                        this.performSearch(true);
                    });
                }

                // Sort dropdown
                if (this.sortDropdown) {
                    this.sortDropdown.addEventListener('change', () => {
                        this.currentPage = 1;
                        this.hasMoreResults = true;
                        this.performSearch(true);
                    });
                }

                // Infinite scroll
                this.setupInfiniteScroll();

                console.log('Event listeners attached, performing initial search...');

                // Initial load
                this.performSearch(true);
            }

            setupInfiniteScroll() {
                let scrollTimeout;

                window.addEventListener('scroll', () => {
                    // Debounce scroll events
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(() => {
                        // Check if user scrolled near bottom
                        const scrollPosition = window.innerHeight + window.scrollY;
                        const pageHeight = document.documentElement.scrollHeight;
                        const threshold = 300; // Load more when 300px from bottom

                        if (scrollPosition >= pageHeight - threshold) {
                            // Only load if not currently loading and has more results
                            if (!this.isLoading && this.hasMoreResults) {
                                console.log('Reached bottom, loading more...');
                                this.currentPage++;
                                this.performSearch(false); // false = append
                            }
                        }
                    }, 100);
                });
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

            async performSearch(clearResults = true) {
                if (this.isLoading) return; // Prevent multiple simultaneous requests

                this.isLoading = true;
                this.showLoading(clearResults);

                const params = new URLSearchParams({
                    q: this.currentQuery,
                    page: this.currentPage,
                    sort: this.sortDropdown?.value || '',
                    ...this.currentFilters
                });

                console.log('Searching with params:', params.toString());

                try {
                    const response = await fetch(`/api/vehicles/search?${params.toString()}`);
                    console.log('Response status:', response.status);

                    const data = await response.json();
                    console.log('Response data:', data);

                    this.totalResultsCount = data.nbHits; // Use renamed variable
                    this.hasMoreResults = this.currentPage < data.nbPages;

                    this.renderResults(data, clearResults);
                    this.updateStats(data);
                    this.updateEndMessage();

                } catch (error) {
                    console.error('Search error:', error);
                    this.showError();
                } finally {
                    this.isLoading = false;
                    this.hideLoading();
                }
            }

            renderResults(data, clearResults = true) {
                console.log('Rendering results:', data.hits?.length || 0, 'vehicles');

                if (!data.hits || data.hits.length === 0) {
                    if (clearResults) {
                        this.resultsContainer.innerHTML = '';
                        this.noResults.classList.remove('hidden');
                    }
                    console.log('No results to display');
                    return;
                }

                this.noResults.classList.add('hidden');

                console.log('First hit sample:', data.hits[0]?.substring(0, 100));

                if (clearResults) {
                    this.resultsContainer.innerHTML = data.hits.join('');
                    this.setupImageLoading();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                } else {
                    // Save scroll position
                    const scrollY = window.scrollY;

                    // Append new content
                    this.resultsContainer.innerHTML += data.hits.join('');
                    this.setupImageLoading();

                    // Maintain scroll position
                    requestAnimationFrame(() => {
                        window.scrollTo({ top: scrollY, behavior: 'auto' });
                    });
                }
            }

            setupImageLoading() {
                // Add loaded class to images when they finish loading
                const images = this.resultsContainer.querySelectorAll('.vehicle-item-img');
                images.forEach(img => {
                    if (img.complete) {
                        // Image already loaded
                        img.classList.add('loaded');
                    } else {
                        // Wait for image to load
                        img.addEventListener('load', function() {
                            this.classList.add('loaded');
                        });
                        img.addEventListener('error', function() {
                            // Even on error, show it (might be broken image icon)
                            this.classList.add('loaded');
                        });
                    }
                });
            }

            updateEndMessage() {
                const endMessage = document.getElementById('end-of-results');
                const totalShown = document.getElementById('total-shown');

                if (!endMessage) return;

                if (!this.hasMoreResults && this.totalResultsCount > 0) {
                    if (totalShown) totalShown.textContent = this.totalResultsCount;
                    endMessage.classList.remove('hidden');
                } else {
                    endMessage.classList.add('hidden');
                }
            }

            updateStats(data) {
                if (this.totalResultsEl) {
                    this.totalResultsEl.textContent = data.nbHits || 0;
                }

                const query = this.currentQuery;
                if (this.searchResultsCount) {
                    if (query) {
                        this.searchResultsCount.textContent = `Found ${data.nbHits} results for "${query}"`;
                    } else {
                        this.searchResultsCount.textContent = `Found ${data.nbHits} vehicles`;
                    }
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
