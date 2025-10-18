{{-- resources/views/categories/index.blade.php --}}
@props([
    'categories',
    'type' => 'Main Category',
    'selectingForCreate' => false,
    'parentCategory' => null,
])

@php
$taxonomyService = app('App\Services\TaxonomyRouteService');
$config = $taxonomyService->getTaxonomyConfig(Str::kebab($type));
@endphp

<x-app-layout :title="$config['pluralType'] ?? 'Categories'">
    <main class="no-padding">
        <x-taxonomy.section
            :categories="$categories"
            :type="$config['type']"
            :pluralType="$config['pluralType']"
            :indexRouteName="$config['indexRouteName']"
            :showRouteName="$config['showRouteName']"
            :createRouteParam="$config['createRouteParam']"
            :selectingForCreate="$selectingForCreate"
            :parentCategory="$parentCategory"
        />
    </main>
</x-app-layout>
