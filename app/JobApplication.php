<?php

namespace App;

use App\User;
use App\Company;
use App\Events\Applied;
use App\JobApplicationDashboard;
use App\Traits\HasMultiplePerJob;
use App\Events\TrackedModelCreated;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Mongodb\Eloquent\Builder;
use Jenssegers\Mongodb\Eloquent\Model as Moloquent;

class JobApplication extends Moloquent
{
    use HasMultiplePerJob;

    protected $connection = 'mongodb';

    public static function submitFor($companySlug, $jobPostingSlug, $userId = null, $resumeSlug = null)
    {
        // create the jobApplication
        $jobApplication = new self();
        $jobApplication->user_id = $userId ?: Auth::user()->id;
        $jobApplication->resumeSlug = $resumeSlug ?: Auth::user()->resumes()->first()->slug;

        // find existing jobApplicationDashboard, or create if doesn't exist yet
        $jobApplicationDashboard = JobApplicationDashboard::findOrCreateForJob($companySlug, $jobPostingSlug);

        // save the jobApplication (just for query efficiencies)
        $jobApplicationDashboard->jobApplications()->save($jobApplication);

        // create a backup in the job_applications collection
        $jobApplication = new self();
        $jobApplication->user_id = $userId ?: Auth::user()->id;
        $jobApplication->resumeSlug = $resumeSlug ?: Auth::user()->resumes()->first()->slug;
        $jobApplication->companySlug = $companySlug;
        $jobApplication->jobPostingSlug = $jobPostingSlug;
        $jobApplication->save();

        // broadcast events
        event(new Applied(Company::findBySlug($companySlug), $jobPostingSlug, Auth::user()));
        event(new TrackedModelCreated($jobApplication, get_called_class()));

        return JobQuestionnaire::findForJob($companySlug, $jobPostingSlug)->requireOnApply ?? false;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
