<?php

namespace App\Magis;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Activity extends MagisModel
{
    protected $casts = [
        'before' => 'json',
        'after' => 'json'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function recordable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
