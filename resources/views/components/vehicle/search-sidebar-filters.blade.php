<div class="search-vehicles-sidebar">
    <div class="card card-found-vehicles">
        <p class="m-0">Found <strong id="total-results">0</strong> vehicles</p>
        <button class="close-filters-button">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" style="width: 24px">
                <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>
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
    </div>
