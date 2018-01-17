<?php

namespace App\Magis\Traits\Models;

use Illuminate\Database\Eloquent\Builder;

trait HasAuthor
{
    /**
     * Filter resource by author
     *
     * @param  Builder $query
     * @param  String  $author
     * @return Builder
     */
    public function scopeAuthor(Builder $query, String $author) : Builder
    {
        return $query->where('author', '=', $author);
    }
}
