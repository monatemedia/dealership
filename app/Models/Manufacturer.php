<?php // app/Models/Manufacturer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Manufacturer extends Model
{
    /** @use HasFactory<\Database\Factories\ManufacturerFactory> */
    use HasFactory, Searchable;
    public $timestamps = false;

    // ADD THESE LINES:
    protected $keyType = 'int';
    public $incrementing = true;

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
        ];
    }

    public function getScoutKey()
    {
        return (string) $this->id;
    }

    // ADD THIS METHOD:
    public function getScoutKeyName()
    {
        return 'id';
    }

    // public function scoutMetadata()
    // {
    //     return [
    //         'id' => $this->getScoutKey(),
    //     ];
    // }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function models(): HasMany
    {
        return $this->hasMany(\App\Models\Model::class);
    }
}
