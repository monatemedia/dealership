<?php // app/Models/DrivetrainGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DrivetrainGroup extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name'];

    public function drivetrains(): HasMany
    {
        return $this->hasMany(Drivetrain::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'drivetrain_group_category')
            ->withPivot('default_drivetrain', 'can_edit');
    }
}
