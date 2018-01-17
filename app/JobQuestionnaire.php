<?php

namespace App;

use App\JobPostingAttachment;
use App\Events\TrackedModelCreated;

class JobQuestionnaire extends JobPostingAttachment
{
    protected $connection = 'mongodb';
    protected $collection = 'job_questionnaires';
    protected $fillable = ['sections', 'requireOnApply'];
    const CREATED_EVENT = TrackedModelCreated::class;

   	public static function requiredOnApplyTo($companySlug, $jobPostingSlug)
    {
    	return static::findForJob($companySlug, $jobPostingSlug)->requireOnApply ?? false;
    }
}
