<?php
// app/Models/AccidentHistoryGroup.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccidentHistoryGroup extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['name'];

    public function accidentHistories(): HasMany
    {
        return $this->hasMany(AccidentHistory::class);
    }

    public function subcategories(): BelongsToMany
    {
        return $this->belongsToMany(Subcategory::class, 'accident_history_group_subcategory')
            ->withPivot('default_accident_history', 'can_edit');
    }
}
