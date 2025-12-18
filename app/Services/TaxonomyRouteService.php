<?php
// app/Services/TaxonomyRouteService.php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TaxonomyRouteService
{
    /**
     * Resolve the show route for a taxonomy category
     *
     * @param string $routeName
     * @param Model $category
     * @return string
     */
    public function resolveShowRoute(string $routeName, Model $category): string
    {
        // Handle VehicleType: requires section -> subcategory -> vehicleType
        if (isset($category->subcategory)) {
            if (isset($category->subcategory->section)) {
                return route($routeName, [
                    'section' => $category->subcategory->section->slug,
                    'subcategory' => $category->subcategory->slug,
                    'vehicleType' => $category->slug,
                ]);
            }
        }

        // Handle Subcategory: requires section -> subcategory
        if (isset($category->section)) {
            return route($routeName, [
                'section' => $category->section->slug,
                'subcategory' => $category->slug,
            ]);
        }

        // Handle Section or simple single-parameter routes
        return route($routeName, $category->slug);
    }

    /**
     * Resolve index route parameters for nested taxonomies
     *
     * @param string $routeName
     * @param Model|null $parentCategory
     * @return array
     */
    public function resolveIndexParams(string $routeName, ?Model $parentCategory = null): array
    {
        // Routes that don't need parameters (show all items globally)
        if (in_array($routeName, ['sections.index'])) {
            return [];
        }

        if (!$parentCategory) {
            return [];
        }

        // Handle section.sub-categories.index route
        // Route: /{section}/sub-categories
        // Parent is a section
        if ($routeName === 'section.sub-categories.index') {
            return [
                'section' => $parentCategory->slug,
            ];
        }

        // Handle vehicle-types.index route
        // Route: /{section}/{subcategory}/vehicle-types
        // Parent is a Subcategory
        if ($routeName === 'vehicle-types.index') {
            if (isset($parentCategory->section)) {
                return [
                    'section' => $parentCategory->section->slug,
                    'subcategory' => $parentCategory->slug,
                ];
            }
        }

        // Handle fuel-types.index route (when you build it)
        // Route: /{section}/{subcategory}/fuel-types
        if ($routeName === 'fuel-types.index') {
            if (isset($parentCategory->section)) {
                return [
                    'section' => $parentCategory->section->slug,
                    'subcategory' => $parentCategory->slug,
                ];
            }
        }

        return [];
    }

    /**
     * Get taxonomy configuration for a given type
     *
     * @param string $type (e.g., 'vehicle-type', 'fuel-type', 'Vehicle Type')
     * @return array
     */
    public function getTaxonomyConfig(string $type): array
    {
        $configs = [
            'section' => [
                'type' => 'Section',
                'pluralType' => 'Sections',
                'indexRouteName' => 'sections.index',
                'showRouteName' => 'sections.show',
                'createRouteParam' => 'section',
            ],
            'sub-category' => [
                'type' => 'Subcategory',
                'pluralType' => 'Sub-Categories',
                'indexRouteName' => 'section.sub-categories.index',
                'showRouteName' => 'sub-categories.show',
                'createRouteParam' => 'subcategory',
            ],
            'vehicle-type' => [
                'type' => 'Vehicle Type',
                'pluralType' => 'Vehicle Types',
                'indexRouteName' => 'vehicle-types.index',
                'showRouteName' => 'vehicle-types.show',
                'createRouteParam' => 'vehicle_type',
            ],
            'fuel-type' => [
                'type' => 'Fuel Type',
                'pluralType' => 'Fuel Types',
                'indexRouteName' => 'fuel-types.index',
                'showRouteName' => 'fuel-types.show',
                'createRouteParam' => 'fuel_type',
            ],
        ];

        // Normalize the type to kebab-case lowercase
        $normalizedType = Str::kebab(Str::lower($type));

        return $configs[$normalizedType] ?? [];
    }

    /**
     * Alias for getTaxonomyConfig (for backwards compatibility)
     */
    public function getConfig(string $type): array
    {
        return $this->getTaxonomyConfig($type);
    }
}
