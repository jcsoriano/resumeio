<?php

namespace App\Magis\Services;

use App\User;
use App\Magis\Socialaccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Contracts\User as ProviderUser;

class SocialAccountService
{
    public static function createGetOrConnectUser($providerName, ProviderUser $providerUser)
    {
        $providerUserId = $providerUser->getId();

        $account = Socialaccount::whereProvider($providerName)
                                ->whereProviderUserId($providerUserId)
                                ->first();

        if (Auth::check()) {
            DB::table('socialaccounts')
                ->whereProviderUserId($providerUserId)
                ->whereProvider($providerName)
                ->update(['user_id' => Auth::user()->id]);
            return Auth::user();
        } elseif ($account) {
            return $account->user;
        } else {
            $account = new SocialAccount([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $providerName
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }
    }
}
