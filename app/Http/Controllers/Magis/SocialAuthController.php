<?php

namespace App\Http\Controllers\Magis;

use Socialite;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Magis\Services\SocialAccountService;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = SocialAccountService::createGetOrConnectUser($provider, Socialite::driver($provider)->user());

        if (!Auth::check()) {
            auth()->login($user);
        }

        return redirect()->to('home');
    }
}
