<?php // app/Models/Province.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Laravel\Scout\Searchable;

class Province extends Model
{
    /** @use HasFactory<\Database\Factories\ProvinceFactory> */
    use HasFactory, Searchable;
    public $timestamps = false;
    protected $fillable = ['name'];
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

    public function getScoutKeyName()
    {
        return 'id';
    }

    public function searchableAs(): string
    {
        return 'provinces';
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function vehicles(): HasManyThrough
    {
        return $this->hasManyThrough(Vehicle::class, City::class);
    }

}
