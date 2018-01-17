<?php

namespace App\Magis\Traits\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

trait HasChangePassword
{
    public function showChangePasswordForm($key = null)
    {
        $resource = $this->model::SLUG;
        $header = $this->getViewHeader($resource, 'change-password');
        return view(
            $this->changePasswordView ?? 'magis.pages.change-password',
            compact('resource', 'header')
        );
    }

    public function attemptChangePassword(Request $request, $key = null)
    {
        // instatiate variables
        $item = null;
        $passwordColumn = $this->passwordColumn ?? 'password';
        
        // get the proper item
        if (empty($key)) {
            if ($this->model == User::class) {
                $item = Auth::user();
            } else {
                return $this->model.' '.User::class;
            }
        } else {
            $item = $this->getItem($key, true, $this->model::withoutGlobalScopes());

            // authorize this item
            $this->authorize('update', $item);
        }

        // validate the request; if the provided password doesn't match what's
        // in the database, throw an error
        Validator::make($request->all(), [
            'old_password' => 'bail|required',
            'new_password' => 'bail|required|confirmed|min:6'
        ])->after(function ($validator) use ($request, $item, $passwordColumn) {
            if (! empty($request->old_password)
                && ! Hash::check($request->old_password, $item->$passwordColumn)
            ) {
                $validator->errors()->add('old_password', 'The password you entered is incorrect.');
            }
        })->validate();

        // passed auth check and validation check; change the password
        $item->$passwordColumn = Hash::make($request->new_password);
        $item->save();

        return view('magis.pages.message', [
            'subject' => 'Successful password change',
            'message' => 'Your password has now been changed',
        ]);
    }
}
