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

        // Handle Subcategory: requires mainCategory -> subCategory
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
        // Routes that don't need parameters (show all items globally)
        if (in_array($routeName, ['main-categories.index'])) {
            return [];
        }

        if (!$parentCategory) {
            return [];
        }

        // Handle main-category.sub-categories.index route
        // Route: /{mainCategory}/sub-categories
        // Parent is a MainCategory
        if ($routeName === 'main-category.sub-categories.index') {
            return [
                'mainCategory' => $parentCategory->slug,
            ];
        }

        // Handle vehicle-types.index route
        // Route: /{mainCategory}/{subCategory}/vehicle-types
        // Parent is a Subcategory
        if ($routeName === 'vehicle-types.index') {
            if (isset($parentCategory->mainCategory)) {
                return [
                    'mainCategory' => $parentCategory->mainCategory->slug,
                    'subCategory' => $parentCategory->slug,
                ];
            }
        }

        // Handle fuel-types.index route (when you build it)
        // Route: /{mainCategory}/{subCategory}/fuel-types
        if ($routeName === 'fuel-types.index') {
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
                'indexRouteName' => 'main-category.sub-categories.index',
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
