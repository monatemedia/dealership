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
                            <select id="provinceSelect" name="province_id">
                                <option value="">Province</option>
                                <option value="4">California</option>
                                <option value="2">Kansas</option>
                                <option value="1">Ohio</option>
                                <option value="5">Oregon</option>
                            </select>
                        </div>
                        <div>
                            <select id="citySelect" name="city_id">
                                <option value="" style="display: block">City</option>
                                <option value="3" data-parent="1" style="display: none">
                                    Carmelstad
                                </option>
                                <option value="8" data-parent="2" style="display: none">
                                    Cormierville
                                </option>
                                <option value="14" data-parent="3" style="display: none">
                                    Dareville
                                </option>
                                <option value="13" data-parent="3" style="display: none">
                                    Demarcotown
                                </option>
                                <option value="10" data-parent="2" style="display: none">
                                    Doylebury
                                </option>
                                <option value="18" data-parent="4" style="display: none">
                                    East Alfonso
                                </option>
                                <option value="9" data-parent="2" style="display: none">
                                    East Ladarius
                                </option>
                                <option value="23" data-parent="5" style="display: none">
                                    Kelvinmouth
                                </option>
                                <option value="24" data-parent="5" style="display: none">
                                    Kemmerchester
                                </option>
                                <option value="25" data-parent="5" style="display: none">
                                    Kunzeview
                                </option>
                                <option value="6" data-parent="2" style="display: none">
                                    Lake Kelsi
                                </option>
                                <option value="16" data-parent="4" style="display: none">
                                    Larsonview
                                </option>
                                <option value="2" data-parent="1" style="display: none">
                                    Lindstad
                                </option>
                                <option value="5" data-parent="1" style="display: none">
                                    Loganshire
                                </option>
                                <option value="15" data-parent="3" style="display: none">
                                    Maximilliaberg
                                </option>
                                <option value="7" data-parent="2" style="display: none">
                                    Monroeside
                                </option>
                                <option value="17" data-parent="4" style="display: none">
                                    Muellerville
                                </option>
                                <option value="12" data-parent="3" style="display: none">
                                    New Bennieville
                                </option>
                                <option value="1" data-parent="1" style="display: none">
                                    New Britneystad
                                </option>
                                <option value="21" data-parent="5" style="display: none">
                                    New Devenmouth
                                </option>
                                <option value="22" data-parent="5" style="display: none">
                                    North Alvah
                                </option>
                                <option value="20" data-parent="4" style="display: none">
                                    Port Johnson
                                </option>
                                <option value="19" data-parent="4" style="display: none">
                                    South Shanellefort
                                </option>
                                <option value="11" data-parent="3" style="display: none">
                                    Toyport
                                </option>
                                <option value="4" data-parent="1" style="display: none">
                                    West Lulu
                                </option>
                            </select>
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
