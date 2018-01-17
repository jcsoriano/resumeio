<?php

namespace App\Http\Controllers\Magis;

use Illuminate\Http\Request;
use App\Magis\NotificationList;
use App\Http\Controllers\Controller;
use App\Magis\Notifications\Contact;
use Illuminate\Support\Facades\Notification;

class ContactController extends Controller
{
    /**
     * Instantiate a new new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    public function show()
    {
        return view('magis.defaults.contact.form');
    }

    public function send(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'bail|required|email',
            'message' => 'required',
        ]);

        Notification::send(
            NotificationList::findBySlug('contact')->users()->get(),
            new Contact($request->except('_token'))
        );

        if ($request->expectsJson()) {
            return [
                'status' => 'success',
            ];
        }

        return view('magis.defaults.contact.thanks');
    }
}
