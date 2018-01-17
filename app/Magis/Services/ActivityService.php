<?php

namespace App\Magis\Services;

use Carbon\Carbon;
use App\Magis\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityService
{
    /**
     * Gets the 10 most recent posts
     */
    public static function recent()
    {
        return Activity::with('recordable', 'user')
                        ->orderBy('created_at', 'desc')
                        ->take(10)
                        ->get();
    }

    /**
     * Gets the 10 most recent activities, formatted
     */
    public static function recentFormatted()
    {
        return self::format(self::recent());
    }

    public static function split($actionName)
    {
        return explode('_', $actionName);
    }

    public static function aAn($subject)
    {
        return in_array(ucfirst(substr($subject, 0, 1)), ['A', 'E', 'I', 'O', 'U']) ? 'an' : 'a';
    }

    public static function myRecent()
    {
        return Activity::with('recordable')
                        ->where('user_id', Auth::user()->id)
                        ->orderBy('created_at', 'desc')
                        ->take(10)
                        ->get();
    }

    public static function myRecentFormatted()
    {
        return self::format(self::myRecent());
    }

    public static function format($activities)
    {
        return array_map(function ($val) {
            list($action, $subject) = self::split($val['name']);
            $hr = isset($val['user']['name']) ? '' : '<hr/>';
            return ($val['user']['name'] ?? 'You').' '.$action.' '.self::aAn($subject).' '.ucfirst($subject).': <a href="'.url(str_plural($subject).'/'.$val['recordable']['slug']).'">'.$val['recordable']['name'].'</a> <small>'.(new Carbon($val['created_at']))->diffForHumans().'</small>'.$hr;
        }, $activities->toArray());
    }
}
