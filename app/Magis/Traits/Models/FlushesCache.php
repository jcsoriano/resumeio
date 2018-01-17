<?php

namespace App\Magis\Traits\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

trait FlushesCache
{
    protected static function bootFlushesCache()
    {
        static::saving(function ($model) {
            Cache::tags([$model::SLUG])->flush();
        });

        static::deleting(function ($model) {
            Cache::tags([$model::SLUG])->flush();
        });
    }
}
