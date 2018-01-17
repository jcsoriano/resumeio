<?php

namespace App\Policies;

use App\User;
use App\JobApplicationDashboard;
use Illuminate\Support\Facades\Auth;
use App\Magis\Services\PolicyService;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobApplicationDashboardPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function view(User $user, JobApplicationDashboard $subject)
    {
        return $user->company->slug == $subject->companySlug;
        // return $this->checkPermission($user, $subject, 'view-job_application_dashboard');
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->is_company;
    }

    /**
     * Determine whether the user can manage users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function manage(User $user)
    {
        return isset(PolicyService::getPermissions($user)['manage-job_application_dashboards']);
    }

    /**
     * Determine whether the user can list users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return isset(PolicyService::getPermissions($user)['list-job_application_dashboards']);
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function update(User $user, JobApplicationDashboard $subject)
    {
        return $user->is_company && $user->company->slug == $subject->companySlug;
    }

    /**
     * Determine whether the user can revert the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function revert(User $user, JobApplicationDashboard $subject)
    {
        return $this->checkPermission($user, $subject, 'revert-job_application_dashboard');
    }

    /**
     * Determine whether the user can restore the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function restore(User $user, JobApplicationDashboard $subject)
    {
        return $this->checkPermission($user, $subject, 'restore-job_application_dashboard');
    }

    /**
     * Determine whether the user can draft the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function draft(User $user, JobApplicationDashboard $subject)
    {
        return $this->checkPermission($user, $subject, 'draft-job_application_dashboard');
    }

    /**
     * Determine whether the user can publish the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function publish(User $user, JobApplicationDashboard $subject)
    {
        return $this->checkPermission($user, $subject, 'publish-job_application_dashboard');
    }

    /**
     * Determine whether the user can preview the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function preview(User $user, JobApplicationDashboard $subject)
    {
        return $this->checkPermission($user, $subject, 'preview-job_application_dashboard');
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function delete(User $user, JobApplicationDashboard $subject)
    {
        return $this->checkPermission($user, $subject, 'delete-job_application_dashboard');
    }

    /**
     * Gets permission either from session or DB
     *
     * @param  User   $user User to be authorized
     * @return array       Permission labels
     */
    private function getPermissions(User $user)
    {
        return PolicyService::getPermissions($user);
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
    private function checkPermission(User $user, JobApplicationDashboard $subject, $label)
    {
        // checking for job_application_dashboard permission is a little different - no need to check for roles
        return $subject->company->user_id == $user->id;
        // return PolicyService::checkPermission($user, $subject, $this->getPermissions($user), $label);
    }
}
