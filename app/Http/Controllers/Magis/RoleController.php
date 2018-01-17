<?php

namespace App\Http\Controllers\Magis;

use App\User;
use App\Magis\Role;
use App\Http\Requests;
use App\Magis\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Magis\Services\PermissionService;

class RoleController extends MagisController
{
    protected $model = Role::class;
    protected $form = 'magis.pages.roles.form';

    /**
     * Saves the role and its relations
     *
     * @param  Request   $request
     * @param  Role|null $role
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request, $item = null)
    {
        $this->validate($request, $this->model::validationRules($item));

        $new = $request->all();
        $permissions = null;
        $users = null;

        if ($request->has('permissions')) {
            $permissions = $request->input('permissions');
            unset($new['permissions']);
        }

        if ($request->has('users')) {
            $users = json_decode($request->input('users'));
            unset($new['users']);
        }

        $role = $item;

        if ($role) {
            $role->update($new);
        } else {
            $role = Role::create($new);
        }

        if ($permissions) {
            $role->setPermissions($permissions);
        }

        if ($request->has('users')) {
            $role->users()->sync($users);
            if (in_array(Auth::user()->id, $users)) {
                session(['permissions' => Auth::user()->permissions()]);
            }
        }
        
        if (request()->expectsJson()) {
            return ['status' => 'success'];
        }
        return redirect('roles/manage');
    }

    /**
     * Get the data used by the form in create and update
     *
     * @param  mixed
     * @return array       The array of values needed by the form
     */
    protected function formData($item = null)
    {
        $data = parent::formData($item);
        $mode = User::count() > config('magis.heavy_threshold') ? 'heavy' : 'light';

        return array_merge($data, [
            'dontdeclareform' => true,
            'object' => 'role',
            'users' => $mode != 'heavy' ? User::with(['roles' => function ($query) {
                $query->select(['id', 'name']);
            }])->select(['id', 'photo', 'name'])->get() : [],
            'mode' => $mode,
            'options' => PermissionService::OPTIONS,
            'permissions' => PermissionService::fromRoutes(),
            'rolePermissions' => $item ? $item->permissions->pluck('option', 'label') : '[]',
            'roleUsers' => $item ? $item->users->pluck('id') : '[]',
        ]);
    }
}
