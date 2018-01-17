<?php

namespace App\Magis\Traits\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

trait SavesSlug
{
    protected static function bootSavesSlug()
    {
        static::creating(function ($model) {
            $slug = Str::slug($model->title ?? $model->name);
            $count = DB::table($model->getTable())->where('slug', $slug)->count();
            if ($count > 0) {
                $id = DB::table($model->getTable())->max('id') + 1;
                $model->slug = $slug.'-'.$id;
            } else {
                $model->slug = $slug;
            }
        });
    }

    public static function scopeFindBySlug(Builder $query, String $slug)
    {
        return $query->where('slug', '=', $slug)->first();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
