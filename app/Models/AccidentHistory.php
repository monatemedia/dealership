<?php
// app/Models/AccidentHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AccidentHistory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'accident_history_group_id'
    ];

    public function accidentHistoryGroup(): BelongsTo
    {
        return $this->belongsTo(AccidentHistoryGroup::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }
}
