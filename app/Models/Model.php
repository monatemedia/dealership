<?php
// app/Models/Model.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Model extends EloquentModel
{
    use HasFactory, Searchable;

    public $timestamps = false;

    protected $fillable = ['name', 'manufacturer_id'];
    protected $keyType = 'int';
    public $incrementing = true;

    public function toSearchableArray(): array
    {
        return [
            'id' => (string) $this->id,
            'name' => (string) ($this->name ?? ''),
            'manufacturer_id' => (int) ($this->manufacturer_id ?? 0),
            'created_at' => 0, // Fixed: No timestamps on this model, so always 0
        ];
    }

    public function getScoutKey(): mixed
    {
        return (string) $this->id;
    }

    public function getScoutKeyName(): mixed
    {
        return 'id';
    }

    public function searchableAs(): string
    {
        return 'models';
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
