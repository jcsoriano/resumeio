<?php

namespace App\Magis\Services;

use App\Magis\Setting;
use Illuminate\Support\Facades\Cache;

class SettingsService
{
    public static function get()
    {
        return self::cacheTag()->remember(
            'all',
            Setting::CACHE_EXPIRY,
            function () {
                return Setting::select(['slug', 'name', 'content'])->get();
            }
        );
    }

    /**
     * Get the value of a setting based on its slug
     *
     * @param  String $slug
     * @return String
     */
    public static function value(String $slug) : String
    {
        return self::cacheTag()->remember(
            $slug,
            Setting::CACHE_EXPIRY,
            function () use ($slug) {
                return Setting::findBySlug($slug)->content;
            }
        );
    }

    private static function cacheTag()
    {
        return Cache::tags([Setting::SLUG, 'get']);
    }
}
