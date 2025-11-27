/* resources/js/VehicleInstantSearch.js */
export class VehicleInstantSearch {
    constructor() {
        // DOM Elements
        this.searchInput = document.getElementById('instant-search-input');
        this.filterForm = document.getElementById('filter-form');
        this.resultsContainer = document.getElementById('search-results');
        this.loadingIndicator = document.getElementById('loading-indicator');
        this.loadMoreIndicator = document.getElementById('load-more-indicator');
        this.noResults = document.getElementById('no-results');
        this.totalResultsEl = document.getElementById('total-results');
        this.searchResultsCount = document.getElementById('search-results-count');
        this.resultsContainer = document.getElementById('search-results');
        this.sortDropdown = document.getElementById('sort-dropdown');

        // State
        this.searchTimeout = null;
        this.currentPage = 1;
        this.currentQuery = '';
        this.currentSort = '';
        this.isLoading = false;
        this.hasMoreResults = true;
        this.totalResultsCount = 0;
        // Filters will contain all form inputs, including geo-search parameters (origin_city_id, range_km)
        this.currentFilters = {};

        // Only init if critical elements exist
        if (this.resultsContainer) {
            this.updateFilters();
            this.init();
        }
    }

    init() {
        // 1. Search Input
        if (this.searchInput) {
            this.searchInput.addEventListener('input', (e) => {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => {
                    this.currentPage = 1;
                    this.currentQuery = e.target.value;
                    this.hasMoreResults = true;
                    this.performSearch(true);
                }, 300);
            });
        }

        // 2. Filter Form (Apply Button)
        const applyBtn = document.getElementById('apply-filters');
        if (applyBtn) {
            applyBtn.addEventListener('click', (e) => {
                e.preventDefault(); // Prevent form submit
                console.log('🔍 Apply filters button clicked');
                this.currentPage = 1;
                this.hasMoreResults = true;
                this.updateFilters(); // Re-read filters
                // 🔑 FIX: Ensure sort value is captured when filters are applied
                if (this.sortDropdown) this.currentSort = this.sortDropdown.value;
                console.log('📊 Current filters:', this.currentFilters);
                this.performSearch(true);
            });
        }

        // 3. Reset Button
        const resetBtn = document.getElementById('reset-filters');
        if (resetBtn) {
            resetBtn.addEventListener('click', () => {
                if (this.filterForm) this.filterForm.reset();
                this.currentFilters = {}; // Reset to empty filters
                this.currentPage = 1;
                this.hasMoreResults = true;
                this.currentSort = '';
                if (this.sortDropdown) this.sortDropdown.value = '';
                this.performSearch(true);
            });
        }

        // 4. Sort Dropdown
        if (this.sortDropdown) {
             // 🔑 FIX: Set initial sort state
             this.currentSort = this.sortDropdown.value;

            this.sortDropdown.addEventListener('change', (e) => {
                this.currentPage = 1;
                this.hasMoreResults = true;
                this.updateFilters();
                // 🔑 CRITICAL FIX: Capture the selected sort value
                this.currentSort = e.target.value;
                this.performSearch(true);
            });
        }

        // 5. Infinite Scroll
        this.setupInfiniteScroll();

        // 6. FIX: Custom Listener for Geo-Search Updates
        // 🔑 FIX: Update to listen for 'filters-applied' from the modal component
        window.addEventListener('filters-applied', () => {
            console.log('Vehicle Instant Search: Received FILTERS APPLIED event. Triggering search...');
            this.currentPage = 1;
            this.hasMoreResults = true;
            this.updateFilters(); // Re-read the newly set hidden inputs
            if (this.sortDropdown) this.currentSort = this.sortDropdown.value; // Capture sort
            this.performSearch(true);
        });

        // 7. Initial Load
        this.performSearch(true);
    }

    setupInfiniteScroll() {
        let scrollTimeout;
        window.addEventListener('scroll', () => {
            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                const scrollPosition = window.innerHeight + window.scrollY;
                const pageHeight = document.documentElement.scrollHeight;
                const threshold = 300;
                if (scrollPosition >= pageHeight - threshold) {
                    if (!this.isLoading && this.hasMoreResults) {
                        this.currentPage++;
                        this.performSearch(false); // false = append
                    }
                }
            }, 100);
        });
    }

    updateFilters() {
        this.currentFilters = {};
        // 1. Read from filter form if it exists (search page)
        if (this.filterForm) {
            const formData = new FormData(this.filterForm);
            for (let [key, value] of formData.entries()) {
                // Only include non-empty values
                // 🔑 FIX 1: Filter out values that are explicitly empty strings, zero, or "null" (though the latter shouldn't happen)
                if (value !== '' && value !== '0' && value !== 'null') {
                    this.currentFilters[key] = value;
                }
            }
        }

        // 2. CRITICAL: Always re-read geo-location filters from specific hidden inputs
        // This is necessary because formData might not correctly capture values set via JS after init.
        const originCityInput = document.getElementById('origin_city_id_filter');
        const rangeKmInput = document.getElementById('range_km_filter');

        // 🔑 CRITICAL FIX 2: Explicitly check for a value for both fields before adding to filters.
        // We only add the city ID if it exists AND is not empty.
        if (originCityInput && originCityInput.value) {
            this.currentFilters['origin_city_id'] = originCityInput.value;
            console.log('📍 Geo-filter: origin_city_id =', originCityInput.value);

            // We only include range_km if the city is set AND the range input has a value.
            if (rangeKmInput && rangeKmInput.value) {
                this.currentFilters['range_km'] = rangeKmInput.value;
                console.log('📏 Geo-filter: range_km =', rangeKmInput.value);
            }
        } else {
             // 🔑 FIX 3: Explicitly ensure range_km is NOT in the filters if origin_city_id is missing.
             // This prevents a previous 'range_km' filter from persisting if the city was cleared.
             delete this.currentFilters['origin_city_id'];
             delete this.currentFilters['range_km'];
        }
    }

    async performSearch(clearResults = true) {
        if (this.isLoading) return;
        this.isLoading = true;
        this.showLoading(clearResults);

        // All necessary filters, including origin_city_id, range_km, and sort are passed to URLSearchParams
        const params = new URLSearchParams({
            q: this.currentQuery,
            page: this.currentPage,
            sort: this.currentSort,
            ...this.currentFilters
        });

        console.log('🌐 API Request URL:', `/api/vehicles/search?${params.toString()}`);

        try {
            const response = await fetch(`/api/vehicles/search?${params.toString()}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();

            // 🔑 IMPORTANT: Verify that the backend is returning the rendered HTML for each hit
            if (data.hits && data.hits.length > 0 && typeof data.hits[0] !== 'string') {
                console.error('BACKEND CHECK FAILED: The frontend requires the "hits" array to contain HTML strings (Blade-rendered cards). Please ensure your Laravel Scout search endpoint renders each hit before returning the JSON payload.');
            }
            this.totalResultsCount = data.nbHits;
            this.hasMoreResults = this.currentPage < data.nbPages;
            this.renderResults(data, clearResults);
            this.updateStats(data);
            this.updateEndMessage();

            // 🆕 CRITICAL ADDITION: Dispatch event after every search with the hit count
            window.dispatchEvent(new CustomEvent('search-updated', {
                detail: { totalHits: this.totalResultsCount }
            }));
        } catch (error) {
            console.error('Search error:', error);
            this.showError();
            // 🆕 Also dispatch update on failure, reporting 0 hits
            window.dispatchEvent(new CustomEvent('search-updated', {
                detail: { totalHits: 0 }
            }));
        } finally {
            this.isLoading = false;
            this.hideLoading();
        }
    }

    renderResults(data, clearResults = true) {
        if (!data.hits || data.hits.length === 0) {
            if (clearResults) {
                this.resultsContainer.innerHTML = '';
                if(this.noResults) this.noResults.classList.remove('hidden');
            }
            return;
        }

        if (this.noResults) this.noResults.classList.add('hidden');

        if (clearResults) {
            this.resultsContainer.innerHTML = data.hits.join('');
            this.setupImageLoading();
        } else {
            // Efficient Append
            const fragment = document.createDocumentFragment();
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.hits.join('');
            while (tempDiv.firstChild) {
                fragment.appendChild(tempDiv.firstChild);
            }
            this.resultsContainer.appendChild(fragment);
            this.setupImageLoading();
        }
    }

    setupImageLoading() {
        const images = this.resultsContainer.querySelectorAll('.vehicle-item-img:not(.loaded)');
        images.forEach(img => {
            if (img.complete) {
                img.classList.add('loaded');
            } else {
                img.addEventListener('load', () => img.classList.add('loaded'));
                img.addEventListener('error', () => img.classList.add('loaded')); // Handle broken images gracefully
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
        if (this.totalResultsEl) this.totalResultsEl.textContent = data.nbHits || 0;
        if (this.searchResultsCount) {
            this.searchResultsCount.textContent = this.currentQuery
                ? `Found ${data.nbHits} results for "${this.currentQuery}"`
                : `Found ${data.nbHits} vehicles`;
        }
    }

    showLoading(clearResults) {
        if (clearResults) {
            if(this.loadingIndicator) this.loadingIndicator.classList.remove('hidden');
            this.resultsContainer.style.opacity = '0.5';
        } else {
            if(this.loadMoreIndicator) this.loadMoreIndicator.classList.remove('hidden');
        }
    }

    hideLoading() {
        if(this.loadingIndicator) this.loadingIndicator.classList.add('hidden');
        if(this.loadMoreIndicator) this.loadMoreIndicator.classList.add('hidden');
        this.resultsContainer.style.opacity = '1';
    }

    showError() {
        // Only replace content if it's a fresh search
        if (this.currentPage === 1) {
            this.resultsContainer.innerHTML = `
                <div class="p-8 text-center bg-red-50 text-red-700 rounded-lg border border-red-200">
                    <h3 class="font-bold text-xl mb-2">Search Error</h3>
                    <p>Unable to load vehicles. Please check your network connection or try again later.</p>
                </div>
            `;
        }
    }
}
