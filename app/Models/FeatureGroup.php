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
     * The categories that can use this feature group
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'feature_group_category')
            ->withPivot('can_edit');
    }
}
