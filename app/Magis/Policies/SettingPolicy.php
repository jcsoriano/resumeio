<?php

namespace App\Magis\Policies;

use App\User;
use App\Magis\Setting;
use Illuminate\Support\Facades\Auth;
use App\Magis\Services\PolicyService;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function view(User $user, Setting $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'view-setting');
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return isset(PolicyService::getPermissions($user)['create-setting']);
    }

    /**
     * Determine whether the user can manage users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function manage(User $user)
    {
        return isset(PolicyService::getPermissions($user)['manage-settings']);
    }

    /**
     * Determine whether the user can list users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return isset(PolicyService::getPermissions($user)['list-settings']);
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function update(User $user, Setting $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'update-setting');
    }

    /**
     * Determine whether the user can revert the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function revert(User $user, Setting $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'revert-setting');
    }

    /**
     * Determine whether the user can restore the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function restore(User $user, Setting $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'restore-setting');
    }

    /**
     * Determine whether the user can draft the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function draft(User $user, Setting $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'draft-setting');
    }

    /**
     * Determine whether the user can publish the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function publish(User $user, Setting $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'publish-setting');
    }

    /**
     * Determine whether the user can preview the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function preview(User $user, Setting $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'preview-setting');
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function delete(User $user, Setting $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'delete-setting');
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
    private function checkPermission(User $user, Setting $subject, $permissions, $label)
    {
        return PolicyService::checkPermission($user, $subject, $permissions, $label);
    }
}
