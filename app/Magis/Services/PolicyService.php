<?php

namespace App\Magis\Services;

use App\User;
use App\Magis\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PolicyService
{
    public static function getPermissions(User $user)
    {
        // if $user is currently logged-in user, the sessions are already in the session
        if ($user->id === Auth::user()->id) {
            return session('permissions');
        } else {
            // fetch the permissions from the DB
            return $user->permissions();
        }
    }

    public static function checkPermission(User $user, $subject, $permissions, $label)
    {
        if (isset($permissions[$label])) {
            $option = $permissions[$label];

            if ($option == 'all') {
                return true;
            } elseif ($option == 'own') {
                return $user->id === $role->user_id;
            } else {
                $key = $option.'_id';
                return $user->$key == $role->$key;
            }
        }
        return false;
    }
}
