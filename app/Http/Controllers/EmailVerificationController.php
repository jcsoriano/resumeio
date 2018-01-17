<?php

namespace App\Http\Controllers;

use App\User;
use App\Events\UserVerified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\VerificationLink;

class EmailVerificationController extends Controller
{
    public function resend()
    {
        $this->middleware('auth');

        // expire the previous verification link
        $user = Auth::user();
        $user->verification_code = str_random(60);
        $user->save();

        $user->notify(new VerificationLink($user));

        return [
            'status' => 'success',
        ];
    }

    public function verify(User $user, $verificationCode)
    {
        if ($user->verified) {
            return redirect('/');
        }

        if ($user->verification_code == $verificationCode) {
            $user->verified = true;
            $user->save();

            event(new UserVerified($user));

            if (!Auth::check()) {
                Auth::login($user);

                return redirect($user->resumes()->first()->slug . '/?introModal=thank-you-for-verifying');
            }

            return redirect($user->resumes()->first()->slug . '/?introModal=thank-you-for-verifying');
        }

        return 'Your verification link has expired or is invalid. Please try again';
    }
}
