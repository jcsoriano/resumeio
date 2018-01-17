<?php

namespace App\Http\Controllers\Magis;

use App\User;
use App\Magis\Role;
use App\Http\Requests;
use App\Magis\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Magis\Services\PermissionService;
use App\Magis\Traits\Controllers\HasChangePassword;

class UserController extends MagisController
{
    // uncomment use HasChangePassword to add change password capability.
    // Remember to add the users/change-password/{key?} routes. You may also 
    // add a change-password-link to the magis-string element of type password.
    //
    // Note that this is not on by default; it is recommended that the only way
    // users can change their password is through the password reset link while
    // logged-out. This is to prevent hacks in long-lived cookies ("Remember me"
    // option) wherein they log-in through a public computer and someone finds it
    // and changes his/her email and password through the profile page.
    // use HasChangePassword;

    protected $model = User::class;

    /**
     * Saves the item and its relations
     *
     * @param  Request   $request
     * @param  mixed|null $item
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, $item = null)
    {
        if ($request->has('password')) {
            $password = $request->input('password');
            $request->merge(['password' => bcrypt($password)]);
        }
        return parent::save($request, $item);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $item = Auth::user();

        if (request()->expectsJson()) {
            return $this->editFormJson($item);
        }

        $formData = $this->formData($item);
        $formData['header'] = $this->getViewHeader('users', 'profile');
        
        return view($this->form ?? 'magis/admin/form', $formData);
    }
}
