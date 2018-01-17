<?php

namespace App\Http\Controllers;

use App\Resume;
use App\Company;
use App\JobPosting;
use App\JobApplication;
use App\JobQuestionnaire;
use Illuminate\Http\Request;
use App\JobApplicationDashboard;
use App\Events\TrackedModelCreated;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Services\NavigationLinkService;
use App\Services\ResumeCleansingService;
use Illuminate\Support\Facades\Validator;

class JobPostingController extends Controller
{
    protected $model = JobPosting::class;

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
    public function store(Request $request)
    {
        // make sure user is logged-in
        $this->middleware('auth');

        // make sure user is authorized
        $this->authorize('create', $this->model);

        if (!Auth::user()->verified) {
            return [
                'status' => 'not-yet-verified',
            ];
        }

        // get previous job postings from this company
        $company = Auth::user()->company;
        $jobPostings = $company->jobPostings;

        // validate the request
        Validator::make($request->all(), [
            'name' => 'bail|required|max:255',
            'slug' => 'bail|required|max:255|alpha_dash',
        ])->after(function ($validator) use ($request, $jobPostings) {
            $slug = $request->slug;
            $slugExists = false;

            // check if slug already exists in company's job postings
            foreach ($jobPostings as $jp) {
                if ($slug == $jp->slug) {
                    $slugExists = true;
                    break;
                }
            }

            if ($slugExists) {
                $validator->errors()->add('slug', 'Your company already has a job posting with the same URL.');
            }
        })->validate();
        
        // fetch the placeholders
        $sampleJobPosting = Resume::findBySlug('sample-job');

        // create the job posting!
        $jobPosting = new JobPosting;

        $jobPosting->type = 'job_posting';
        $jobPosting->user_id = Auth::user()->id;
        $jobPosting->slug = $request->slug;
        $jobPosting->profile = [
            'name' => $request->name,
            'position' => $company->profile['name'],
        ];

        if (isset($company->bannerPicture) && !empty($company->bannerPicture)) {
            $jobPosting->bannerPicture = $company->bannerPicture;
        }

        if (isset($company->profilePicture) && !empty($company->profilePicture)) {
            $jobPosting->profilePicture = $company->profilePicture;
        }
        
        // assign the placeholders
        $jobPosting->leftSnapshot = $company->leftSnapshot;
        $jobPosting->rightSnapshot = $company->rightSnapshot;
        $jobPosting->sections = $sampleJobPosting->sections;
        
        $company->jobPostings()->save($jobPosting);

        // save a job posting for management and metrics purposes
        $jobPosting = new JobPosting;
        $jobPosting->user_id = Auth::user()->id;
        $jobPosting->slug = $request->slug;
        $jobPosting->companySlug = $company->slug;
        $jobPosting->save();

        event(new TrackedModelCreated($jobPosting, JobPosting::class));

        return [
            'status' => 'success',
            'slug' => $company->slug . '/' . $jobPosting->slug,
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
        $resume = $company->findJobPostingOrFail($jobPostingSlug);
        $slug = $resume->slug;
        $companySlug = $company->slug;
        $resumeType = 'job_posting';
        $canEdit = Auth::check() && Auth::user()->can('update', $resume);
        $questionnaire = true;
        $hasApplied = JobApplicationDashboard::hasAppliedTo($company->slug, $jobPostingSlug);
        $leftNavigationLinks = NavigationLinkService::leftNavigationLinks(
            $resumeType,
            $canEdit,
            $companySlug,
            $jobPostingSlug,
            $hasApplied
        );
        $rightNavigationLinks = NavigationLinkService::rightNavigationLinks($canEdit);
        $actionPointOptions = NavigationLinkService::actionPointOptions(
            $resumeType,
            $canEdit,
            $hasApplied
        );

        if (!Auth::check()) {
            $applyTo = $companySlug . '/' . $jobPostingSlug;
        } else {
            if ($canEdit) {
                $editing = true;

                // load the jobApplicationDashboard if it exists
                $jobApplicationDashboard = JobApplicationDashboard::findForJob($companySlug, $jobPostingSlug);
                $resume->jobApplicationDashboard = $jobApplicationDashboard ?: [];
                $jobQuestionnaire = JobQuestionnaire::findForJob($companySlug, $jobPostingSlug);
                $resume->jobQuestionnaire = $jobQuestionnaire ?: ['sections' => []];
                if ($jobApplicationDashboard) {
                    $resume->jobApplicationDashboard->users = $jobApplicationDashboard->users();
                    $resume->jobApplicationDashboard->jobApplications = JobApplication::findAllForJob($companySlug, $jobPostingSlug);
                    $resume->jobApplicationDashboard->jobQuestionnaireAnswers = $jobApplicationDashboard->jobQuestionnaireAnswers();
                }
            }

            if (Auth::user()->is_company) {
                $isCompany = true;
            }
        }

        // TODO: show optimized resume version
        // TODO: Cache full responses when appropriate?
        return view('pages.app', compact(
            'resume',
            'leftNavigationLinks',
            'isCompany',
            'slug',
            'editing',
            'rightNavigationLinks',
            'actionPointOptions',
            'applyTo',
            'questionnaire'
        ));
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
        // ensure logged-in
        $this->middleware('auth');

        // fetch the job posting
        $resume = $company->findJobPostingOrFail($jobPostingSlug);

        // make sure user is authorized
        $this->authorize('update', $resume);

        // update the resume
        $resume->update((new ResumeCleansingService)->cleanse($request->all()));

        // respond success
        return [
            'status' => 'success',
        ];
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
