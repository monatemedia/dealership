        <!-- Find a vehicle form -->
        <section class="find-a-vehicle">
            <div class="container">
                <form action={{ route('vehicle.search') }} method="GET" class="find-a-vehicle-form card flex p-medium">
                    <div class="find-a-vehicle-inputs">
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
                            <x-select-vehicle-type />
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
                            <x-select-fuel-type />
                        </div>
                    </div>
                    <div>
                        <button type="button" class="btn btn-find-a-vehicle-reset">
                            Reset
                        </button>
                        <button class="btn btn-primary btn-find-a-vehicle-submit">
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </section>
        <!--/ Find a vehicle form -->
