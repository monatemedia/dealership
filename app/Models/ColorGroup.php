<?php
// app/Models/ColorGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ColorGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function colors(): HasMany
    {
        return $this->hasMany(Color::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'color_group_category')
            ->withPivot('default_color', 'can_edit');
    }
}
