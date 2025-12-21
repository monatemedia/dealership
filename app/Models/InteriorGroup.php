<?php
// app/Models/InteriorGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InteriorGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function interiors(): HasMany
    {
        return $this->hasMany(Interior::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'interior_group_category')
            ->withPivot('default_interior', 'can_edit');
    }
}
