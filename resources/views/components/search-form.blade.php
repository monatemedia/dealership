        <!-- Find a car form -->
        <section class="find-a-car">
            <div class="container">
                <form action={{ route('car.search') }} method="GET" class="find-a-car-form card flex p-medium">
                    <div class="find-a-car-inputs">
                        <div>
                            <x-select-manufacturer />
                        </div>
                        <div>
                            <x-select-model />
                        </div>
                        <div>
                            <x-select-province />
                        </div>
                        <div>
                            <x-select-city />
                        </div>
                        <div>
                            <select name="car_type_id">
                                <option value="">Type</option>
                                <option value="2">Hatchback</option>
                                <option value="6">Jeep</option>
                                <option value="5">Minivan</option>
                                <option value="4">Pickup Truck</option>
                                <option value="3">SUV</option>
                                <option value="1">Sedan</option>
                            </select>
                        </div>
                        <div>
                            <input type="number" placeholder="Year From" name="year_from" />
                        </div>
                        <div>
                            <input type="number" placeholder="Year To" name="year_to" />
                        </div>
                        <div>
                            <input type="number" placeholder="Price From" name="price_from" />
                        </div>
                        <div>
                            <input type="number" placeholder="Price To" name="price_to" />
                        </div>
                        <div>
                            <select name="fuel_type_id">
                                <option value="">Fuel Type</option>
                                <option value="2">Diesel</option>
                                <option value="3">Electric</option>
                                <option value="1">Gasoline</option>
                                <option value="4">Hybrid</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-find-a-car-reset">
                            Reset
                        </button>
                        <button class="btn btn-primary btn-find-a-car-submit">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </section>
        <!--/ Find a car form -->
