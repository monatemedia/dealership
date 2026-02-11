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
    protected $fillable = ['name', 'source', 'last_ai_check_at', 'ai_retry_count'];
    public $timestamps = false;

    // ADD THESE LINES:
    protected $keyType = 'int';
    public $incrementing = true;

    public function toSearchableArray(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => (string) ($this->name ?? ''),
            'created_at' => 0, // Fixed: No timestamps on this model, so always 0
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

    public function searchableAs(): string
    {
        return 'manufacturers';
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function models(): HasMany
    {
        return $this->hasMany(\App\Models\Model::class);
    }

    public function aliases() {
        return $this->hasMany(ManufacturerAlias::class);
    }
}
