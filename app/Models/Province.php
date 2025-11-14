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

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function vehicles(): HasManyThrough
    {
        return $this->hasManyThrough(Vehicle::class, City::class);
    }

}
