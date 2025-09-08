@php
    $isInWatchlist = $vehicle->isInWatchlist(\Illuminate\Support\Facades\Auth::user())
@endphp

<x-app-layout title="{{ $vehicle->manufacturer->name }} {{ $vehicle->model->name }} - {{ $vehicle->year }}">

    <main>
        <div class="container">
            <h1 class="vehicle-details-page-title">{{ $vehicle->manufacturer->name }} {{ $vehicle->model->name }} - {{ $vehicle->year }}</h1>
            <div class="vehicle-details-region">{{ $vehicle->city->name}} - {{ $vehicle->published_at->toDateString() }}</div>

            <div class="vehicle-details-content">
                <div class="vehicle-images-and-description">
                    <div class="vehicle-images-carousel">
                        <div class="vehicle-image-wrapper">
                            <img
                            src="{{ $vehicle->primaryImage?->getUrl() ?: '/img/no_image.png' }}"
                            alt="" class="vehicle-active-image"
                            id="activeImage"
                        />
                        </div>
                        <div class="vehicle-image-thumbnails">
                            @foreach($vehicle->images as $image)
                                <img src="{{ $image->getUrl() }}" alt=""/>
                            @endforeach
                        </div>
                        <button class="carousel-button prev-button" id="prevButton">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="width: 64px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                            </svg>
                        </button>
                        <button class="carousel-button next-button" id="nextButton">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" style="width: 64px">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>

                    <div class="card vehicle-detailed-description">
                        <h2 class="vehicle-details-title">Detailed Description</h2>
                        <p>
                            {!! $vehicle->description !!}
                        </p>
                    </div>

                    <div class="card vehicle-detailed-description">
                        <h2 class="vehicle-details-title">Vehicle Specifications</h2>

                        <ul class="vehicle-specifications">
                            <x-vehicle-specification :value="$vehicle->features->abs">
                                ABS
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->air_conditioning">
                                Air Conditioning
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->power_windows">
                                Power Windows
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->power_door_locks">
                                Power Door Locks
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->cruise_control">
                                Cruise Control
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->bluetooth_connectivity">
                                Bluetooth Connectivity
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->remote_start">
                                Remote Start
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->gps_navigation">
                                GPS Navigation System
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->heated_seats">
                                Heated Seats
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->climate_control">
                                Climate Control
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->rear_parking_sensors">
                                Rear Parking Sensors
                            </x-vehicle-specification>
                            <x-vehicle-specification :value="$vehicle->features->leather_seats">
                                Leather Seats
                            </x-vehicle-specification>
                        </ul>
                    </div>
                </div>
                <div class="vehicle-details card">
                    <div class="flex items-center justify-between">
                        <p class="vehicle-details-price">${{ $vehicle->price}}</p>
                        <button
                            class="btn-heart text-primary"
                            data-url="{{ route('watchlist.storeDestroy', $vehicle) }}"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                style="width: 16px"
                                @class(['hidden' => $isInWatchlist])>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                            </svg>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                                style="width: 16px"
                                @class(['hidden' => !$isInWatchlist])>
                                <path
                                    d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z"
                                />
                            </svg>
                        </button>
                    </div>

                    <hr />
                    <table class="vehicle-details-table">
                        <tbody>
                            <tr>
                                <th>Manufacturer</th>
                                <td>{{ $vehicle->manufacturer->name }}</td>
                            </tr>
                            <tr>
                                <th>Model</th>
                                <td>{{ $vehicle->model->name}}</td>
                            </tr>
                            <tr>
                                <th>Year</th>
                                <td>{{ $vehicle->year }}</td>
                            </tr>
                            <tr>
                                <th>VIN</th>
                                <td>{{ $vehicle->vin }}</td>
                            </tr>
                            <tr>
                                <th>Milage</th>
                                <td>{{ $vehicle->mileage }}</td>
                            </tr>
                            <tr>
                                <th>Vehicle Type</th>
                                <td>{{ $vehicle->vehicleType->name}}</td>
                            </tr>
                            <tr>
                                <th>Fuel Type</th>
                                <td>{{ $vehicle->fuelType->name }}</td>
                            </tr>
                            <tr>
                                <th>Address</th>
                                <td>{{ $vehicle->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr />

                    <div class="flex gap-1 my-medium">
                        <img src="/img/avatar.png" alt="" class="vehicle-details-owner-image" />
                        <div>
                            <h3 class="vehicle-details-owner">{{ $vehicle->owner->name }}</h3>
                            <div class="text-muted">{{ $vehicle->owner->vehicles()->count() }} vehicles</div>
                        </div>
                    </div>
                    <a href="tel:{{ Str::mask($vehicle->phone, '*', -3)}}" class="vehicle-details-phone">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" style="width: 16px">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>

                        <span class="text-phone">
                            {{ Str::mask($vehicle->phone, '*', -3)}}
                        </span>
                        <span class="vehicle-details-phone-view"
                            data-url="{{ route('vehicle.showPhone', $vehicle) }}">
                            view full number
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </main>
</x-app-layout>
