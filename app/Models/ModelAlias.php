<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ModelAlias extends EloquentModel
{
    public $timestamps = false;

    protected $fillable = ['alias', 'model_id'];

    public function model(): BelongsTo
    {
        return $this->belongsTo(Model::class);
    }
}
