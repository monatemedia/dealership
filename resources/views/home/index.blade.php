@php
$taxonomyService = app('App\Services\TaxonomyRouteService');
$config = $taxonomyService->getConfig('main-category');
@endphp

<x-app-layout title="Home Page">
    <x-hero.home-slider />

    <main>
        <x-taxonomy.section
            :categories="$categories"
            :type="$config['type']"
            :pluralType="$config['pluralType']"
            :indexRouteName="$config['indexRouteName']"
            :showRouteName="$config['showRouteName']"
        />

        <x-search-form />

        {{-- Latest Vehicles Section --}}
        <section>
            <div class="container">
                <h2>Latest Vehicles Added</h2>

                @if ($vehicles->count() > 0)
                    <div class="vehicle-items-listing">
                        @foreach($vehicles as $vehicle)
                            <x-vehicle-item
                                :$vehicle
                                :is-in-watchlist="$vehicle->favouredUsers->contains(Auth::user())"
                            />
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-large">There are no published vehicles.</div>
                @endif

                {{ $vehicles->onEachSide(1)->links() }}
            </div>
        </section>
    </main>
</x-app-layout>
