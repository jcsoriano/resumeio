<?php

namespace App\Magis\Policies;

use App\User;
use App\Magis\NotificationList;
use Illuminate\Support\Facades\Auth;
use App\Magis\Services\PolicyService;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotificationListPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the NotificationList.
     *
     * @param  App\User  $user
     * @param  App\NotificationList  $subject
     * @return mixed
     */
    public function view(User $user, NotificationList $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'view-notification_list');
    }

    /**
     * Determine whether the user can create NotificationList.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return isset(PolicyService::getPermissions($user)['create-notification_list']);
    }

    /**
     * Determine whether the user can manage NotificationList.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function manage(User $user)
    {
        return isset(PolicyService::getPermissions($user)['manage-notification_lists']);
    }

    /**
     * Determine whether the user can list NotificationList.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return isset(PolicyService::getPermissions($user)['list-notification_lists']);
    }

    /**
     * Determine whether the user can update the NotificationList.
     *
     * @param  App\User  $user
     * @param  App\NotificationList  $subject
     * @return mixed
     */
    public function update(User $user, NotificationList $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'update-notification_list');
    }

    /**
     * Determine whether the user can revert the NotificationList.
     *
     * @param  App\User  $user
     * @param  App\NotificationList  $subject
     * @return mixed
     */
    public function revert(User $user, NotificationList $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'revert-notification_list');
    }

    /**
     * Determine whether the user can restore the NotificationList.
     *
     * @param  App\User  $user
     * @param  App\NotificationList  $subject
     * @return mixed
     */
    public function restore(User $user, NotificationList $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'restore-notification_list');
    }

    /**
     * Determine whether the user can draft the NotificationList.
     *
     * @param  App\User  $user
     * @param  App\NotificationList  $subject
     * @return mixed
     */
    public function draft(User $user, NotificationList $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'draft-notification_list');
    }

    /**
     * Determine whether the user can publish the NotificationList.
     *
     * @param  App\User  $user
     * @param  App\NotificationList  $subject
     * @return mixed
     */
    public function publish(User $user, NotificationList $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'publish-notification_list');
    }

    /**
     * Determine whether the user can schedule the NotificationList.
     *
     * @param  App\User  $user
     * @param  App\NotificationList  $subject
     * @return mixed
     */
    public function schedule(User $user, NotificationList $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'schedule-notification_list');
    }

    /**
     * Determine whether the user can preview the NotificationList.
     *
     * @param  App\User  $user
     * @param  App\NotificationList  $subject
     * @return mixed
     */
    public function preview(User $user, NotificationList $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'preview-notification_list');
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  App\User  $user
     * @param  App\NotificationList  $subject
     * @return mixed
     */
    public function delete(User $user, NotificationList $subject)
    {
        return $this->checkPermission($user, $subject, PolicyService::getPermissions($user), 'delete-notification_list');
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
    private function checkPermission(User $user, NotificationList $subject, $permissions, $label)
    {
        return PolicyService::checkPermission($user, $subject, $permissions, $label);
    }
}
