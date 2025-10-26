<?php // app/Models/Feature.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Feature extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    /**
     * The feature groups this feature belongs to
     */
    public function featureGroups(): BelongsToMany
    {
        return $this->belongsToMany(FeatureGroup::class);
    }

    /**
     * The vehicles that have this feature
     */
    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'feature_vehicle');
    }
}
