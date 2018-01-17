<?php

namespace App\Policies;

use App\User;
use App\JobQuestionnaireAnswer;
use Illuminate\Support\Facades\Auth;
use App\Magis\Services\PolicyService;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobQuestionnaireAnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function view(User $user, JobQuestionnaireAnswer $subject)
    {
        // if company, it MUST be the company who created the job posting
        if ($user->is_company) {    
            return isset($user->company->findJobPosting($subject->jobPostingSlug)->profile);
        }

        // if user, then user MUST have applied first
        return $user->jobApplications->where('companySlug', '=', $subject->companySlug)
                ->where('jobPostingSlug', '=', $subject->jobPostingSlug)
                ->where('user_id', '=', $user->id)
                ->count() > 0;
    }

    /**
     * Determine whether the user can create users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return !$user->is_company;
    }

    /**
     * Determine whether the user can manage users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function manage(User $user)
    {
        return isset(PolicyService::getPermissions($user)['manage-job_questionnaires']);
    }

    /**
     * Determine whether the user can list users.
     *
     * @param  App\User  $user
     * @return mixed
     */
    public function list(User $user)
    {
        return isset(PolicyService::getPermissions($user)['list-job_questionnaires']);
    }

    /**
     * Determine whether the user can update the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function update(User $user, JobQuestionnaireAnswer $subject)
    {
        return $user->id == $subject->user_id;
    }

    /**
     * Determine whether the user can revert the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function revert(User $user, JobQuestionnaireAnswer $subject)
    {
        return $this->checkPermission($user, $subject, 'revert-job_questionnaire');
    }

    /**
     * Determine whether the user can restore the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function restore(User $user, JobQuestionnaireAnswer $subject)
    {
        return $this->checkPermission($user, $subject, 'restore-job_questionnaire');
    }

    /**
     * Determine whether the user can draft the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function draft(User $user, JobQuestionnaireAnswer $subject)
    {
        return $this->checkPermission($user, $subject, 'draft-job_questionnaire');
    }

    /**
     * Determine whether the user can publish the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function publish(User $user, JobQuestionnaireAnswer $subject)
    {
        return $this->checkPermission($user, $subject, 'publish-job_questionnaire');
    }

    /**
     * Determine whether the user can preview the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function preview(User $user, JobQuestionnaireAnswer $subject)
    {
        return $this->checkPermission($user, $subject, 'preview-job_questionnaire');
    }

    /**
     * Determine whether the user can delete the user.
     *
     * @param  App\User  $user
     * @param  App\User  $subject
     * @return mixed
     */
    public function delete(User $user, JobQuestionnaireAnswer $subject)
    {
        return $this->checkPermission($user, $subject, 'delete-job_questionnaire');
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
    private function checkPermission(User $user, JobQuestionnaireAnswer $subject, $label)
    {
        // checking for job_questionnaire permission is a little different - no need to check for roles
        return $subject->company->user_id == $user->id;
        // return PolicyService::checkPermission($user, $subject, $this->getPermissions($user), $label);
    }
}