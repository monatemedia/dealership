        <!-- resources/views/components/search-form.blade.php -->
        <!-- Find a vehicle form -->
        <section class="section-search">
            <div class="container">
                {{-- The snippet you provided --}}
                <div class="mb-medium">
                    <div class="find-a-vehicle-form card p-medium">
                        <div class="form-group">
                            <label class="mb-medium" style="display:block; font-weight:600;">Search Vehicles</label>

                            {{--
                            CRITICAL: id="instant-search-input"
                            This connects to your VehicleInstantSearch.js
                            --}}
                            <input
                                type="text"
                                id="instant-search-input"
                                placeholder="Search by make, model, location, type..."
                                style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 4px;"
                            />

                            <small class="text-muted" style="display:block; margin-top: 8px;">
                                Start typing to search instantly
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--/ Find a vehicle form -->
