<?php

namespace App\Http\Controllers;

use App\User;
use App\Resume;
use App\JobPosting;
use App\JobApplication;
use App\MetricSnapshot;
use App\JobQuestionnaire;
use Illuminate\Http\Request;
use App\JobQuestionnaireAnswer;
use App\Magis\Services\AuthService;
use Illuminate\Support\Facades\Auth;

class MetricsDashboardController extends Controller
{
    public function show()
    {
        // make sure logged-in
        $this->middleware('auth');

        // make sure authorized
        AuthService::authorize('dashboard');

        // get snapshots
        $snapshots = MetricSnapshot::all();

        // show current
        $metrics = $this->snapshot();

        // keys
        $keys = array_keys($metrics);

        $userId = Auth::user()->id;

        // show view file
        return view('pages.dashboard', compact('snapshots', 'metrics', 'keys', 'userId'));
    }

    public function snapshot()
    {
        return [
            'users' => User::count(),
            'user_verified' => User::where('verified', '=', 1)->count(),
            'resumes' => Resume::count(),
            'individual_verified' => User::verified()->individuals()->count(),
            'companies' => User::companies()->count(),
            'company_verified' => User::companies()->verified()->count(),
            'job_postings' => JobPosting::count(),
            'job_questionnaires' => JobQuestionnaire::count(),
            'job_applications' => JobApplication::count(),
            'job_questionnaire_answers' => JobQuestionnaireAnswer::count(),
        ];
    }
}
