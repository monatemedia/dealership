{{-- resources/views/categories/index.blade.php --}}
{{-- @if(config('app.debug'))
    <div style="background: yellow; padding: 20px; margin: 20px;">
        <strong>Debug Info:</strong><br>
        selectingForCreate: {{ $selectingForCreate ? 'TRUE' : 'FALSE' }}<br>
        Session value: {{ session('selecting_category_for_create') ? 'TRUE' : 'FALSE' }}<br>
        Flash messages: {{ json_encode(session()->all()) }}
    </div>
@endif --}}

@props([
    'categories',
    'type' => 'Section',
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
        'createRouteParam' => 'category',
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
            :createRouteParam="$config['createRouteParam'] ?? 'category'"
            :selectingForCreate="$selectingForCreate"
            :parentCategory="$parentCategory"
        />
    </main>
</x-app-layout>
