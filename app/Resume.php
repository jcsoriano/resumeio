<?php

namespace App;

use App\User;
use App\Traits\ExtractsExcerpt;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

class Resume extends Moloquent
{
    use ExtractsExcerpt;

    protected $connection = 'mongodb';
    protected $fillable = [
        'profile', 'bannerPicture', 'profilePicture', 'leftSnapshot', 'rightSnapshot', 'sections'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public static function scopeFindBySlug(Builder $query, String $slug)
    {
        return $query->where('slug', '=', $slug)->first();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
