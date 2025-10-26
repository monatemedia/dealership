<?php // app/Models/FeatureGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FeatureGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    /**
     * The features that belong to this group
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(Feature::class);
    }

    /**
     * The subcategories that can use this feature group
     */
    public function subcategories(): BelongsToMany
    {
        return $this->belongsToMany(Subcategory::class, 'feature_group_subcategory')
            ->withPivot('can_edit');
    }
}
