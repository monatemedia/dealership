<?php // app/Models/FuelTypeGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FuelTypeGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function fuelTypes(): HasMany
    {
        return $this->hasMany(FuelType::class);
    }

    public function subCategories(): BelongsToMany
    {
        return $this->belongsToMany(SubCategory::class, 'fuel_type_group_sub_category')
            ->withPivot('default_fuel_type', 'can_edit');
    }
}
