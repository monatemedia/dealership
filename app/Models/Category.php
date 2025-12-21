<?php // app/Models/Category.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'categories';

    protected $fillable = [
        'name',
        'singular',
        'long_name',
        'description',
        'image_path',
        'slug',
        'section_id',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
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
        return $this->belongsToMany(FuelTypeGroup::class, 'fuel_type_group_category')
            ->withPivot('default_fuel_type', 'can_edit');
    }

    // ADD THIS
    public function transmissionGroups(): BelongsToMany
    {
        return $this->belongsToMany(TransmissionGroup::class, 'transmission_group_category')
            ->withPivot('default_transmission', 'can_edit');
    }

    // ADD THIS
    public function drivetrainGroups(): BelongsToMany
    {
        return $this->belongsToMany(DrivetrainGroup::class, 'drivetrain_group_category')
            ->withPivot('default_drivetrain', 'can_edit');
    }

    /**
     * Get all available fuel types for this category
     */
    public function availableFuelTypes()
    {
        $groupIds = $this->fuelTypeGroups()->pluck('fuel_type_groups.id');
        return FuelType::whereIn('fuel_type_group_id', $groupIds)->get();
    }

    /**
     * Get the fuel type configuration for this category
     */
    public function getFuelTypeConfig(): array
    {
        $groups = $this->fuelTypeGroups()->get();
        if ($groups->isEmpty()) {
            return [
                'can_edit' => true,
                'default' => null,
                'fuel_types' => collect([])
            ];
        }
        $fuelTypes = $this->availableFuelTypes();
        $firstGroup = $groups->first();
        return [
            'can_edit' => $firstGroup->pivot->can_edit,
            'default' => $firstGroup->pivot->default_fuel_type,
            'fuel_types' => $fuelTypes
        ];
    }

    // ADD THIS
    /**
     * Get all available transmissions for this category
     */
    public function availableTransmissions()
    {
        $groupIds = $this->transmissionGroups()->pluck('transmission_groups.id');
        return Transmission::whereIn('transmission_group_id', $groupIds)->get();
    }

    // ADD THIS
    /**
     * Get the transmission configuration for this category
     */
    public function getTransmissionConfig(): array
    {
        $groups = $this->transmissionGroups()->get();
        if ($groups->isEmpty()) {
            return [
                'can_edit' => true,
                'default' => null,
                'transmissions' => collect([])
            ];
        }
        $transmissions = $this->availableTransmissions();
        $firstGroup = $groups->first();
        return [
            'can_edit' => $firstGroup->pivot->can_edit,
            'default' => $firstGroup->pivot->default_transmission,
            'transmissions' => $transmissions
        ];
    }

    // ADD THIS
    /**
     * Get all available drive trains for this category
     */
    public function availableDrivetrains()
    {
        $groupIds = $this->drivetrainGroups()->pluck('drivetrain_groups.id');
        return Drivetrain::whereIn('drivetrain_group_id', $groupIds)->get();
    }

    /**
     * Get the drive train configuration for this category
     */
    public function getDrivetrainConfig(): array
    {
        $groups = $this->drivetrainGroups()->get();
        if ($groups->isEmpty()) {
            return [
                'can_edit' => true,
                'default' => null,
                'drivetrains' => collect([])
            ];
        }
        $drivetrains = $this->availableDrivetrains();
        $firstGroup = $groups->first();
        return [
            'can_edit' => $firstGroup->pivot->can_edit,
            'default' => $firstGroup->pivot->default_drivetrain,
            'drivetrains' => $drivetrains
        ];
    }

    public function colorGroups(): BelongsToMany
    {
        return $this->belongsToMany(ColorGroup::class, 'color_group_category')
            ->withPivot('default_color', 'can_edit');
    }

    public function interiorGroups(): BelongsToMany
    {
        return $this->belongsToMany(InteriorGroup::class, 'interior_group_category')
            ->withPivot('default_interior', 'can_edit');
    }

    public function accidentHistoryGroups(): BelongsToMany
    {
        return $this->belongsToMany(AccidentHistoryGroup::class, 'accident_history_group_category')
            ->withPivot('default_accident_history', 'can_edit');
    }

    /**
     * Get all available colors for this category
     */
    public function availableColors()
    {
        $groupIds = $this->colorGroups()->pluck('color_groups.id');
        return Color::whereIn('color_group_id', $groupIds)->get();
    }

    /**
     * Get the color configuration for this category
     */
    public function getColorConfig(): array
    {
        $groups = $this->colorGroups()->get();
        if ($groups->isEmpty()) {
            return [
                'can_edit' => true,
                'default' => null,
                'colors' => collect([])
            ];
        }
        $colors = $this->availableColors();
        $firstGroup = $groups->first();
        return [
            'can_edit' => $firstGroup->pivot->can_edit,
            'default' => $firstGroup->pivot->default_color,
            'colors' => $colors
        ];
    }

    /**
     * Get all available interiors for this category
     */
    public function availableInteriors()
    {
        $groupIds = $this->interiorGroups()->pluck('interior_groups.id');
        return Interior::whereIn('interior_group_id', $groupIds)->get();
    }

    /**
     * Get the interior configuration for this category
     */
    public function getInteriorConfig(): array
    {
        $groups = $this->interiorGroups()->get();
        if ($groups->isEmpty()) {
            return [
                'can_edit' => true,
                'default' => null,
                'interiors' => collect([])
            ];
        }
        $interiors = $this->availableInteriors();
        $firstGroup = $groups->first();
        return [
            'can_edit' => $firstGroup->pivot->can_edit,
            'default' => $firstGroup->pivot->default_interior,
            'interiors' => $interiors
        ];
    }

    /**
     * Get all available accident histories for this category
     */
    public function availableAccidentHistories()
    {
        $groupIds = $this->accidentHistoryGroups()->pluck('accident_history_groups.id');
        return AccidentHistory::whereIn('accident_history_group_id', $groupIds)->get();
    }

    /**
     * Get the accident history configuration for this category
     */
    public function getAccidentHistoryConfig(): array
    {
        $groups = $this->accidentHistoryGroups()->get();
        if ($groups->isEmpty()) {
            return [
                'can_edit' => true,
                'default' => null,
                'accident_histories' => collect([])
            ];
        }
        $accidentHistories = $this->availableAccidentHistories();
        $firstGroup = $groups->first();
        return [
            'can_edit' => $firstGroup->pivot->can_edit,
            'default' => $firstGroup->pivot->default_accident_history,
            'accident_histories' => $accidentHistories
        ];
    }

    /**
     * Get all available features for this category
     */
    public function availableFeatures()
    {
        $groupIds = $this->featureGroups()->pluck('feature_groups.id');
        return Feature::whereIn('feature_group_id', $groupIds)->get();
    }

    /**
     * Get feature configuration for this category
     * Returns: [
     *   'can_edit' => bool,
     *   'groups' => Collection (grouped features by group name),
     *   'features' => Collection (all features flat)
     * ]
     */
    public function getFeatureConfig(): array
    {
        // Get all feature groups for this category with their features
        $featureGroups = $this->featureGroups()
            ->with('features')
            ->get();

        if ($featureGroups->isEmpty()) {
            return [
                'can_edit' => true,
                'groups' => collect([]),
                'features' => collect([])
            ];
        }

        // Check if user can edit (use the first group's pivot value, or true if all have same value)
        $canEdit = $featureGroups->first()->pivot->can_edit ?? true;

        // Group features by their group name
        $groupedFeatures = $featureGroups->mapWithKeys(function ($group) {
            return [$group->name => $group->features];
        });

        // Flatten all features into a single collection
        $allFeatures = $featureGroups->pluck('features')->flatten();

        return [
            'can_edit' => $canEdit,
            'groups' => $groupedFeatures,
            'features' => $allFeatures
        ];
    }

    // Make sure you have the featureGroups relationship defined
    public function featureGroups(): BelongsToMany
    {
        return $this->belongsToMany(FeatureGroup::class, 'feature_group_category')
            ->withPivot('can_edit');
    }
}
