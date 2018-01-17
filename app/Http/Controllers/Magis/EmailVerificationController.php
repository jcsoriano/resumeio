<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function attempt($email = null, $token = null)
    {
        if (empty($email) || empty($token)) {
            redirect('verify-reminder');
        }

        $user = User::where('email', '=', $email)
            ->where('verified', '=', false)
            ->where('verification_code', '=', $token)
            ->first();
        if (empty($user)) {
            // show that user may already be verified or the token may have expired
        } else {
            // show that verification was successful
        }
    }

    public function sendVerificationLink()
    {
        // change the verification_code after every request
    }

    public function verificationRequest($token = null)
    {
        // change the verification_code after every request
    }
}
