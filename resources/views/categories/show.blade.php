{{-- resources/views/categories/show.blade.php --}}
@props([
    'category',
    'vehicles',
    'childCategories' => null,
    'childCategoryType' => null,
    'parentCategory' => null,
])

@php
$taxonomyService = app('App\Services\TaxonomyRouteService');
$config = [];
$typeKey = null;

if ($childCategoryType) {
    // Convert to kebab-case for config lookup
    $typeKey = Str::kebab(Str::lower($childCategoryType));
    $config = $taxonomyService->getTaxonomyConfig($typeKey);
}
@endphp

<x-app-layout :title="$category->name">
    <x-hero.home-slider />

    <main>
        {{-- DEBUG: Remove this section after troubleshooting --}}
        @if(config('app.debug'))
            <div style="background: #f0f0f0; padding: 20px; margin: 20px; border: 2px solid #ccc; font-family: monospace; font-size: 12px;">
                <h3 style="margin-top: 0;">🔍 Debug Info for Taxonomy Section</h3>
                <ul style="list-style: none; padding: 0;">
                    <li>✓ <strong>childCategoryType:</strong> <code>{{ var_export($childCategoryType, true) }}</code></li>
                    <li>✓ <strong>typeKey (computed):</strong> <code>{{ var_export($typeKey, true) }}</code></li>
                    <li>✓ <strong>config found:</strong> <code>{{ empty($config) ? '❌ NO' : '✅ YES' }}</code></li>
                    @if(!empty($config))
                        <li style="margin-left: 20px;">→ <strong>type:</strong> {{ $config['type'] ?? 'N/A' }}</li>
                        <li style="margin-left: 20px;">→ <strong>pluralType:</strong> {{ $config['pluralType'] ?? 'N/A' }}</li>
                        <li style="margin-left: 20px;">→ <strong>showRouteName:</strong> {{ $config['showRouteName'] ?? 'N/A' }}</li>
                    @endif
                    <li>✓ <strong>childCategories exists:</strong> <code>{{ $childCategories ? '✅ YES' : '❌ NO' }}</code></li>
                    <li>✓ <strong>childCategories count:</strong> <code>{{ $childCategories?->count() ?? 0 }}</code></li>
                    <li>✓ <strong>parentCategory:</strong> <code>{{ $parentCategory?->name ?? 'NULL' }}</code></li>
                    <li style="margin-top: 10px; padding: 10px; background: {{ ($childCategories && $childCategories->isNotEmpty() && !empty($config)) ? '#d4edda' : '#f8d7da' }}; border-radius: 4px;">
                        <strong>🎯 Section will render:</strong>
                        <strong>{{ ($childCategories && $childCategories->isNotEmpty() && !empty($config)) ? '✅ YES' : '❌ NO' }}</strong>
                    </li>
                </ul>
            </div>
        @endif
        {{-- END DEBUG --}}

        {{-- Child taxonomy section (vehicle types, fuel types, etc.) --}}
        @if ($childCategories && $childCategories->isNotEmpty() && !empty($config))
            <x-taxonomy.section
                :categories="$childCategories"
                :type="$config['type']"
                :pluralType="$config['pluralType']"
                :indexRouteName="$config['indexRouteName']"
                :showRouteName="$config['showRouteName']"
                :selectingForCreate="false"
                :parentCategory="$parentCategory"
            />
        @endif

        <x-search-form />

        {{-- Vehicle listing section --}}
        <section>
            <div class="container">
                <h2>
                    @if ($parentCategory)
                        {{ $category->name }} - {{ $parentCategory->name }}
                    @else
                        Latest {{ $category->name }}
                    @endif
                </h2>

                @if ($vehicles->count() > 0)
                    <div class="vehicle-items-listing">
                        @foreach($vehicles as $vehicle)
                            <x-vehicle-item
                                :$vehicle
                                :is-in-watchlist="$vehicle->favouredUsers->contains(
                                    \Illuminate\Support\Facades\Auth::user()
                                )"
                            />
                        @endforeach
                    </div>
                @else
                    <div class="text-center p-large">
                        There are no published vehicles in this category.
                    </div>
                @endif

                {{ $vehicles->onEachSide(1)->links() }}
            </div>
        </section>
    </main>
</x-app-layout>
