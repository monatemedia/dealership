<?php
// app/Models/Color.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Color extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'color_group_id'
    ];

    public function colorGroup(): BelongsTo
    {
        return $this->belongsTo(ColorGroup::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
