<?php

namespace App\Magis\Services;

class AssetService
{
    public static function path($path)
    {
        if (config('app.env') == 'production') {
            return url(elixir($path));
        }
        return url($path);
    }
}
