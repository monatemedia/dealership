<?php
// app/Services/TaxonomyRouteService.php
namespace App\Services;

use Illuminate\Database\Eloquent\Model;

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
        // Handle VehicleType: requires mainCategory -> subCategory -> vehicleType
        if (isset($category->subCategory)) {
            if (isset($category->subCategory->mainCategory)) {
                return route($routeName, [
                    'mainCategory' => $category->subCategory->mainCategory->slug,
                    'subCategory' => $category->subCategory->slug,
                    'vehicleType' => $category->slug,
                ]);
            }
        }

        // Handle SubCategory: requires mainCategory -> subCategory
        if (isset($category->mainCategory)) {
            return route($routeName, [
                'mainCategory' => $category->mainCategory->slug,
                'subCategory' => $category->slug,
            ]);
        }

        // Handle MainCategory or simple single-parameter routes
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
        if (!$parentCategory) {
            return [];
        }

        // Handle vehicle-types.index route
        if ($routeName === 'vehicle-types.index') {
            if (isset($parentCategory->mainCategory)) {
                return [
                    'mainCategory' => $parentCategory->mainCategory->slug,
                    'subCategory' => $parentCategory->slug,
                ];
            }
        }

        // Handle fuel-types.index route (when you build it)
        if ($routeName === 'fuel-types.index') {
            // Similar pattern for fuel types
            if (isset($parentCategory->mainCategory)) {
                return [
                    'mainCategory' => $parentCategory->mainCategory->slug,
                    'subCategory' => $parentCategory->slug,
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
            'main-category' => [
                'type' => 'Main Category',
                'pluralType' => 'Main Categories',
                'indexRouteName' => 'main-categories.index',
                'showRouteName' => 'main-categories.show',
                'createRouteParam' => 'main_category',
            ],
            'sub-category' => [
                'type' => 'Sub-Category',
                'pluralType' => 'Sub-Categories',
                'indexRouteName' => 'sub-categories.index',
                'showRouteName' => 'sub-categories.show',
                'createRouteParam' => 'sub_category',
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
        $normalizedType = \Illuminate\Support\Str::kebab(\Illuminate\Support\Str::lower($type));

        return $configs[$normalizedType] ?? [];
    }
}
