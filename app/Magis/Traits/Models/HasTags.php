<?php

namespace App\Magis\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait HasTags
{
    /**
     * Filter resource according to tags
     * @param  Builder $query
     * @param  String  $tag
     * @return Builder
     */
    public function scopeTag(Builder $query, String $tag) : Builder
    {
        return $query->where('tags', 'like', '%'.$tag.'%');
    }
}
