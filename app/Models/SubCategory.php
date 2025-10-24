<?php // app/Models/SubCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubCategory extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'sub_categories';

    protected $fillable = [
        'name',
        'singular',
        'long_name',
        'description',
        'image_path',
        'slug',
        'main_category_id',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function mainCategory(): BelongsTo
    {
        return $this->belongsTo(MainCategory::class);
    }

    public function vehicleTypes(): HasMany
    {
        return $this->hasMany(VehicleType::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function fuelTypeGroups(): BelongsToMany
    {
        return $this->belongsToMany(FuelTypeGroup::class, 'fuel_type_group_sub_category')
            ->withPivot('default_fuel_type', 'can_edit');
    }

    /**
     * Get all available fuel types for this sub-category
     */
    public function availableFuelTypes()
    {
        $groupIds = $this->fuelTypeGroups()->pluck('fuel_type_groups.id');

        // Get all fuel types for the groups
        $fuelTypes = FuelType::whereIn('fuel_type_group_id', $groupIds)->get();

        return $fuelTypes;
    }

    /**
     * Get the fuel type configuration for this sub-category
     */
    public function getFuelTypeConfig(): array
    {
        $groups = $this->fuelTypeGroups()->get();

        if ($groups->isEmpty()) {
            return [
                'can_edit' => true,
                'default' => null,
                'fuel_types' => collect([]) // Empty collection
            ];
        }

        $fuelTypes = $this->availableFuelTypes();

        // Get the first group's pivot data for defaults
        $firstGroup = $groups->first();

        return [
            'can_edit' => $firstGroup->pivot->can_edit,
            'default' => $firstGroup->pivot->default_fuel_type,
            'fuel_types' => $fuelTypes
        ];
    }
}
