<?php

namespace App;

use App\User;
use App\JobQuestionnaire;
use App\Traits\HasMultiplePerJob;
use Illuminate\Support\Facades\Auth;

class JobQuestionnaireAnswer extends Resume
{
    use HasMultiplePerJob;
    
    protected $connection = 'mongodb';
    protected $collection = 'job_questionnaire_answers';
    protected $fillable = ['sections'];

    public static function findForJob($companySlug, $jobPostingSlug, int $userId = null)
    {
        // if not logged-in, return nothing
        if (!Auth::check()) {
            return null;
        }

        // if userId isset, must be a company
        if ($userId) {
            if (!Auth::user()->is_company) {
                return null;
            }
        }

        return static::where('companySlug', '=', $companySlug)
                ->where('jobPostingSlug', '=', $jobPostingSlug)
                ->where('user_id', '=', $userId ?: Auth::user()->id)
                ->first()
            ?: JobQuestionnaire::findForJobOrFail($companySlug, $jobPostingSlug);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
