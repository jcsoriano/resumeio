<?php

namespace App\Magis\Policies;

use App\User;
use App\Magis\Role;
use Illuminate\Support\Facades\Auth;
use App\Magis\Services\PolicyService;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the role.
     *
     * @param  App\User  $user
     * @param  App\Role  $role
     * @return mixed
     */
    public function view(User $user, Role $role)
    {
        return $this->checkPermission($user, $role, PolicyService::getPermissions($user), 'view-role');
    }

    /**
     * Determine whether the user can create roles.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return isset(PolicyService::getPermissions($user)['create-role']);
    }

    /**
     * Determine whether the user can manage roles.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function manage(User $user)
    {
        return isset(PolicyService::getPermissions($user)['manage-roles']);
    }

    /**
     * Determine whether the user can list roles.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return isset(PolicyService::getPermissions($user)['list-roles']);
    }

    /**
     * Determine whether the user can update the role.
     *
     * @param  App\User  $user
     * @param  App\Role  $role
     * @return mixed
     */
    public function update(User $user, Role $role)
    {
        return $this->checkPermission($user, $role, PolicyService::getPermissions($user), 'update-role');
    }

    /**
     * Determine whether the user can revert the role.
     *
     * @param  App\User  $user
     * @param  App\Role  $role
     * @return mixed
     */
    public function revert(User $user, Role $role)
    {
        return $this->checkPermission($user, $role, PolicyService::getPermissions($user), 'revert-role');
    }

    /**
     * Determine whether the user can restore the role.
     *
     * @param  App\User  $user
     * @param  App\Role  $role
     * @return mixed
     */
    public function restore(User $user, Role $role)
    {
        return $this->checkPermission($user, $role, PolicyService::getPermissions($user), 'restore-role');
    }

    /**
     * Determine whether the user can draft the role.
     *
     * @param  App\User  $user
     * @param  App\Role  $role
     * @return mixed
     */
    public function draft(User $user, Role $role)
    {
        return $this->checkPermission($user, $role, PolicyService::getPermissions($user), 'draft-role');
    }

    /**
     * Determine whether the user can publish the role.
     *
     * @param  App\User  $user
     * @param  App\Role  $role
     * @return mixed
     */
    public function publish(User $user, Role $role)
    {
        return $this->checkPermission($user, $role, PolicyService::getPermissions($user), 'publish-role');
    }

    /**
     * Determine whether the user can preview the role.
     *
     * @param  App\User  $user
     * @param  App\Role  $role
     * @return mixed
     */
    public function preview(User $user, Role $role)
    {
        return $this->checkPermission($user, $role, PolicyService::getPermissions($user), 'preview-role');
    }

    /**
     * Determine whether the user can delete the role.
     *
     * @param  App\User  $user
     * @param  App\Role  $role
     * @return mixed
     */
    public function delete(User $user, Role $role)
    {
        return $this->checkPermission($user, $role, PolicyService::getPermissions($user), 'delete-role');
    }

    /**
     * Checks if a user has a specific permission
     *
     * @param  User   $user        User to be authorized
     * @param  Role   $role        Object to check on
     * @param  array $permissions Permission labels of user
     * @param  string $label       Permission to be checked on
     * @return boolean              Whether the user is authorized
     */
    private function checkPermission(User $user, Role $role, $permissions, $label)
    {
        return PolicyService::checkPermission($user, $role, $permissions, $label);
    }
}
