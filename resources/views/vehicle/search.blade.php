{{-- resources/views/vehicle/search.blade.php --}}
<x-app-layout title="Search">
    <main>
        <section>

            <x-search-form /> {{-- Reusing the component from index.blade.php --}}

            <div class="container">

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
                    <x-vehicle.search-sidebar-filters />
                    <x-vehicle.search-results-list />
                </div>

            </div>
        </section>
    </main>
</x-app-layout>
