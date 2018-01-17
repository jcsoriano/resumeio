<?php

namespace App\Http\Controllers;

use App\Company;
use App\JobApplication;
use App\JobQuestionnaire;
use Illuminate\Http\Request;
use App\JobApplicationDashboard;
use Illuminate\Support\Facades\Auth;

class JobApplicationController extends Controller
{
    protected $model = JobApplication::class;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Company $company, $jobPostingSlug)
    {
        // if not logged-in, go the signup-to-apply path
        if (!Auth::check()) {
            return [
                'status' => 'signup-to-apply',
            ];
        }

        if (!Auth::user()->verified) {
            return [
                'status' => 'not-yet-verified',
            ];
        }

        // make sure user is authorized to apply
        $this->authorize('create', JobApplication::class);

        // save this into a variable
        $companySlug = $company->slug;

        // fetch job posting, and throw an error if it doesn't exist
        $jobPosting = $company->findJobPostingOrFail($jobPostingSlug);

        // check if user hasn't applied to it already
        if (JobApplicationDashboard::hasAppliedTo($companySlug, $jobPostingSlug)) {
            return [
                'status' => 'already-applied',
            ];
        }

        $hasQuestionnaire = JobApplication::submitFor($companySlug, $jobPostingSlug);

        // create a job, check if there's a questionnaire, and return success!
        return [
            'status' => 'success',
            'hasQuestionnaire' => $hasQuestionnaire,
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, $jobPostingSlug)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company, $jobPostingSlug)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
