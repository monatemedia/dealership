{{-- resources/views/categories/index.blade.php --}}
@props([
    'categories',
    'type' => 'Main Category',
    'selectingForCreate' => false,
    'parentCategory' => null,
])

@php
use Illuminate\Support\Str;

$taxonomyService = app('App\Services\TaxonomyRouteService');
$config = $taxonomyService->getConfig($type);

// If config is empty, provide defaults to prevent errors
if (empty($config)) {
    $config = [
        'type' => $type,
        'pluralType' => Str::plural($type),
        'indexRouteName' => '#',
        'showRouteName' => '#',
        'createRouteParam' => 'sub_category',
    ];
}
@endphp

<x-app-layout :title="$config['pluralType'] ?? 'Categories'">
    <main class="no-padding">
        <x-taxonomy.section
            :categories="$categories"
            :type="$config['type']"
            :pluralType="$config['pluralType']"
            :indexRouteName="$config['indexRouteName']"
            :showRouteName="$config['showRouteName']"
            :createRouteParam="$config['createRouteParam'] ?? 'sub_category'"
            :selectingForCreate="$selectingForCreate"
            :parentCategory="$parentCategory"
        />
    </main>
</x-app-layout>
