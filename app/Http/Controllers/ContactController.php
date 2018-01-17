<?php

namespace App\Http\Controllers;

use App\Resume;
use Illuminate\Http\Request;
use App\Notifications\Contact;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request, Resume $resume)
    {
        // validate the request
        Validator::make($request->all(), [
            'message' => 'required',
        ])->after(function ($validator) use ($request) {
            if (!Auth::check()) {
                if (!$request->has('email') || empty($request->input('email'))) {
                    $validator->errors()->add('email', 'Please enter your email.');
                }

                if (!$request->has('name') || empty($request->input('name'))) {
                    $validator->errors()->add('name', 'Please enter your name.');
                }
            }
        })->validate();

        $this->validate($request, [
            'message' => 'required|min:1',
        ]);

        Notification::send(
            $resume->user,
            new Contact([
                'message' => $request->message,
                'name' => Auth::check() ? Auth::user()->name : $request->name,
                'email' => Auth::check() ? Auth::user()->email : $request->email,
                'url' => Auth::check() ? url(Auth::user()->resumes()->first()->slug) : '',
            ])
        );

        return [
            'status' => 'success',
        ];
    }
}
