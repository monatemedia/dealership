<?php // app/Models/Model.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Model extends EloquentModel
{
    /** @use HasFactory<\Database\Factories\ModelFactory> */
    use HasFactory, Searchable;
    public $timestamps = false;

    protected $fillable = ['name', 'manufacturer_id'];

    // ADD THESE LINES:
    protected $keyType = 'int';
    public $incrementing = true;

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'manufacturer_id' => $this->manufacturer_id,
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

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
