<?php

namespace App\Http\Controllers;

use App\JobApplicationDashboard;
use App\Http\Controllers\JobPostingAttachmentController;
use App\Services\JobApplicationDashboardCleansingService;

class JobApplicationDashboardController extends JobPostingAttachmentController
{
    protected $model = JobApplicationDashboard::class;
    protected $cleansingService = JobApplicationDashboardCleansingService::class;
}
