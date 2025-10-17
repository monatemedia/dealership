@props(['vehicle', 'isInWatchlist' => false])

<div class="vehicle-item card">
    <a href="{{ route('vehicle.show', $vehicle) }}">
        <img
        src="{{ $vehicle->primaryImage?->getUrl() ?: '/img/no_image.png' }}"
        alt=""
        class="vehicle-item-img rounded-t"
    />
    </a>
    <div class="p-medium">
        <div class="flex items-center justify-between">
            <small class="m-0 text-muted">{{ $vehicle->city->name }}, {{ $vehicle->city->province->name }}</small>
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
        <h2 class="vehicle-item-title">
            {{ $vehicle->getTitle() }}
        </h2>
        <p class="vehicle-item-price">R {{ number_format($vehicle->price, 0, '.', ' ') }}</p>
        <hr />
        <div class="vehicle-item-badges">
            {{-- @dd($vehicle) --}}
            <span class="vehicle-item-badge">{{ $vehicle->mainCategory->name }}</span>
            <span class="vehicle-item-badge">{{ $vehicle->subCategory->name }}</span>
            <span class="vehicle-item-badge">{{ $vehicle->vehicleType?->name }}</span>
            <span class="vehicle-item-badge">{{ $vehicle->fuelType?->name }}</span>
        </div>
    </div>
</div>
