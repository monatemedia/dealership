@php // resources/views/home/index.blade.php
$taxonomyService = app('App\Services\TaxonomyRouteService');
$config = $taxonomyService->getConfig('section');
@endphp

<x-app-layout title="Home Page" :managed-footer="true">

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
        <x-vehicle.search-results-display />

    </main>

</x-app-layout>
