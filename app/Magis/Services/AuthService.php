<?php

namespace App\Magis\Services;

use Illuminate\Support\Facades\App;

class AuthService
{
    /**
     * Check if current user has the particular permission
     *
     * @param  String  $permission [description]
     * @return boolean             [description]
     */
    public static function hasPermission(String $permission)
    {
        return isset(session('permissions')[$permission]);
    }

    /**
     * Check if current user has the particular permission
     *
     * @param  String  $permission [description]
     * @return boolean             [description]
     */
    public static function authorize(String $permission)
    {
        if (!isset(session('permissions')[$permission])) {
            App::abort(403, 'You are not authorized to visit this page');
        }
    }
}
