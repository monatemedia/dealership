<?php // app/Models/DriveTrainGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DriveTrainGroup extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['name'];

    public function driveTrains(): HasMany
    {
        return $this->hasMany(DriveTrain::class);
    }

    public function subCategories(): BelongsToMany
    {
        return $this->belongsToMany(SubCategory::class, 'drive_train_group_sub_category')
            ->withPivot('default_drive_train', 'can_edit');
    }
}
