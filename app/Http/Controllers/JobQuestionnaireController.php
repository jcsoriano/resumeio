<?php

namespace App\Http\Controllers;

use App\JobQuestionnaire;
use App\Http\Controllers\JobPostingAttachmentController;
use App\Services\JobQuestionnaireCleansingService;

class JobQuestionnaireController extends JobPostingAttachmentController
{
    protected $model = JobQuestionnaire::class;
    protected $cleansingService = JobQuestionnaireCleansingService::class;
}
