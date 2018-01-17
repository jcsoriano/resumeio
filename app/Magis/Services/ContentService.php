<?php

namespace App\Magis\Services;

use Settings;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ContentService
{
    public static function optimizeImages($item, $column = 'content')
    {
        // unfortunately, magis-img breaks when used in summernote air editor
        if (Auth::check() && Auth::user()->can('update', $item)) {
            return $item->$column;
        }

        return str_replace('<img', '<magis-img', $item->$column);
    }

    public static function title($item, $column = 'name')
    {
        $title = $item->name ?? Settings::value('site-title');

        if (isset($item->is_draft)) {
            $title .= $item->is_draft ? '<i>(Draft)</i>' : '';
        }

        if (isset($item->published_at)) {
            $title .= Carbon::now()->lt($item->published_at) ? '<i>(Scheduled)</i>' : '';
        }

        return $title;
    }

    public static function excerpt($item, $column = 'content', $length = '250', $excerptColumn = 'excerpt')
    {
        if (isset($item->$excerptColumn) && ! empty($item->$excerptColumn)) {
            return $item->$excerptColumn;
        }

        // substr the whole content first so that you won't have to strip_tags through
        // the whole content (since it's usually mediumText, it might take up to 64KB
        // worth of string)
        return substr(strip_tags(substr($item->$column, 0, $length * 3)), 0, $length);
    }

    public static function meta()
    {
        return 'magis.defaults.content.meta';
    }
}
