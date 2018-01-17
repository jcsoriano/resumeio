<?php

namespace App\Magis\Traits\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

trait EnforcesPublishedScope
{
    protected static function bootEnforcesPublishedScope()
    {
        static::addGlobalScope('published', function (Builder $builder) {
            $builder->where('is_draft', '=', '0')
                    ->where('published_at', '<=', Carbon::now());
        });

        static::creating(function ($model) {
            if ($model->is_draft) {
                $model->drafted_by = Auth::user()->id ?? 0;
                $model->drafted_at = Carbon::now();
            } else {
                $model->published_by = Auth::user()->id ?? 0;
                if (empty($model->published_at)) {
                    $model->published_at = Carbon::now();
                }
            }
        });

        static::updating(function ($model) {
            $fresh = $model->fresh();
            if ($fresh->is_draft == '0' && $model->is_draft == '1') {
                $model->drafted_by = Auth::user()->id ?? 0;
                $model->drafted_at = Carbon::now();
            }
            if (empty($model->published_at) && $fresh->is_draft == '1' && $model->is_draft == '0') {
                $model->published_by = Auth::user()->id ?? 0;
                $model->published_at = Carbon::now();
            }
        });
    }

    public static function scopeGroupedByPublishedMonth(Builder $query, $order = 'asc')
    {
        return $query->orderBy('published_at', $order)->get()->groupBy(function ($item) {
            return $item->published_at->format('Y-m');
        });
    }

    public static function scopeDrafts(Builder $query): Builder
    {
        return $query->where('is_draft', '=', '1');
    }

    public static function scopeScheduled(Builder $query): Builder
    {
        return $query->where('is_draft', '=', '0')
                    ->where('published_at', '>', Carbon::now());
    }

    public static function recent($num, $column = 'published_at')
    {
        return self::orderBy($column, 'desc')->take($num)->get();
    }
}
