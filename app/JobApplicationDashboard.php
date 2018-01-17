<?php

namespace App;

use App\User;
use App\JobApplication;
use App\JobPostingAttachment;
use App\JobQuestionnaireAnswer;
use Illuminate\Support\Facades\Auth;

class JobApplicationDashboard extends JobPostingAttachment
{
    protected $connection = 'mongodb';
    protected $collection = 'job_application_dashboards';
    protected $fillable = ['stages'];

    public static function hasAppliedTo($companySlug, $jobPostingSlug)
    {
        // if the person is not signed-in, the person hasn't applied yet
        if (!Auth::check()) {
            return false;
        }

        $jobApplicationDashboard = static::findForJob($companySlug, $jobPostingSlug);

        // if it doesn't exist, then user hasn't applied yet
        if (!isset($jobApplicationDashboard->companySlug)) {
            return false;
        }

        return $jobApplicationDashboard->jobApplications()->where('user_id', '=', Auth::user()->id)->count() > 0;
    }

    public function jobApplications()
    {
        return $this->embedsMany(JobApplication::class);
    }

    public function users()
    {
        $userIds = JobApplication::findAllForJob($this->companySlug, $this->jobPostingSlug)->pluck('user_id');
        return User::whereIn('id', $userIds)->get()->keyBy('id');
    }

    public function jobQuestionnaireAnswers()
    {
        return JobQuestionnaireAnswer::findAllForJob($this->companySlug, $this->jobPostingSlug)->pluck('user_id', 'user_id');
    }
}
