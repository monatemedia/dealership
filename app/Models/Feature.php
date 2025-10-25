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
        'feature_group_id'
    ];

    public function featureGroup(): BelongsTo
    {
        return $this->belongsTo(FeatureGroup::class);
    }

    public function vehicles(): BelongsToMany
    {
        return $this->belongsToMany(Vehicle::class, 'feature_vehicle');
    }
}
