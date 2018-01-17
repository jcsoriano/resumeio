<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetricSnapshot extends Model
{
    protected $fillable = [
        'users',
        'user_verified',
        'resumes',
        'resume_verified',
        'companies',
        'company_verified',
        'job_postings',
        'job_questionnaires',
        'job_applications',
        'job_questionnaire_answers',
    ];
}
