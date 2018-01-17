<?php

namespace App\Magis\Traits\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

trait SavesCreatedUpdatedBy
{
    protected static function bootSavesCreatedUpdatedBy()
    {
        static::creating(function ($model) {
            $model->created_by = Auth::user()->id ?? 0;
        });

        static::saving(function ($model) {
            $model->updated_by = Auth::user()->id ?? 0;
        });
    }

    public static function scopeMine(Builder $query) : Builder
    {
        return $query->where('created_by', '=', Auth::user()->id);
    }
}
