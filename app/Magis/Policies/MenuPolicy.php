<?php

namespace App\Magis\Policies;

use App\User;
use App\Magis\Menu;
use Illuminate\Support\Facades\Auth;
use App\Magis\Services\PolicyService;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function view(User $user, Menu $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'view-menu');
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return isset(PolicyService::getPermissions($user)['create-menu']);
    }

    /**
     * Determine whether the user can manage users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function manage(User $user)
    {
        return isset(PolicyService::getPermissions($user)['manage-menus']);
    }

    /**
     * Determine whether the user can list users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return isset(PolicyService::getPermissions($user)['list-menus']);
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function update(User $user, Menu $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'update-menu');
    }

    /**
     * Determine whether the user can revert the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function revert(User $user, Menu $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'revert-menu');
    }

    /**
     * Determine whether the user can restore the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function restore(User $user, Menu $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'restore-menu');
    }

    /**
     * Determine whether the user can draft the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function draft(User $user, Menu $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'draft-menu');
    }

    /**
     * Determine whether the user can publish the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function publish(User $user, Menu $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'publish-menu');
    }

    /**
     * Determine whether the user can preview the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function preview(User $user, Menu $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'preview-menu');
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function delete(User $user, Menu $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'delete-menu');
    }

    /**
     * Gets permission either from session or DB
     *
     * @param  User   $user User to be authorized
     * @return array       Permission labels
     */
    private function getPermissions(User $user)
    {
        //if $user is currentlly logged-in user, the sessions are already in the session
        if ($user->id === Auth::user()->id) {
            return session('permissions');
        } else {
            //fetch the permissions from the DB
            return $user->permissions();
        }
    }

    /**
     * Checks if a user has a specific permission
     *
     * @param  User   $user        User to be authorized
     * @param  User   $subject        Object to check on
     * @param  array $permissions Permission labels of user
     * @param  string $label       Permission to be checked on
     * @return boolean              Whether the user is authorized
     */
    private function checkPermission(User $user, Menu $subject, $permissions, $label)
    {
        return PolicyService::checkPermission($user, $subject, $permissions, $label);
    }
}
