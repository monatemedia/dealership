<?php
// app/Models/Interior.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Interior extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'interior_group_id'
    ];

    public function interiorGroup(): BelongsTo
    {
        return $this->belongsTo(InteriorGroup::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
