<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use App\JobQuestionnaire;
use Illuminate\Http\Request;
use App\JobQuestionnaireAnswer;
use App\Events\TrackedModelCreated;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\NavigationLinkService;
use Illuminate\Support\Facades\Validator;
use App\Services\JobQuestionnaireCleansingService;
use App\Services\JobQuestionnaireAnswerCleansingService;

class JobQuestionnaireAnswerController extends Controller
{
    protected $model = JobQuestionnaire::class;
    protected $cleansingService = JobQuestionnaireCleansingService::class;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // do nothing
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // do nothing
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // do nothing
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, $jobPostingSlug, $userId = null)
    {
        // make sure logged-in
        $this->middleware('auth');

        // save companySlug in variable
        $companySlug = $company->slug;

        // fetch JobQuestionnaireAnswer for this user, or the blank JobQuestionnaire
        $jobQuestionnaireOrAnswer = JobQuestionnaireAnswer::findForJob($companySlug, $jobPostingSlug, $userId);

        // more graceful authorization-handling
        if (!Auth::check() || !Auth::user()->can('view', $jobQuestionnaireOrAnswer)) {
            return redirect($companySlug . '/' . $jobPostingSlug);
        }

        
        // construct the resume (get details applicant or job posting)
        if ($userId) {
            $resume = User::find($userId)->resumes()->first();

            // transfer the resume's section to the resume property
            $resume->resume = $resume->sections;
        } else {
            $resume = $company->findJobPostingOrFail($jobPostingSlug);
        }

        $resumeType = 'job_questionnaire_answer';
        $resume->sections = $jobQuestionnaireOrAnswer->sections;
        $resume->type = $resumeType;

        // get application data attributes
        $questionnaire = true;
        $slug = $resume->slug;
        $rightNavigationLinks = NavigationLinkService::rightNavigationLinks();
        $actionPointOptions = NavigationLinkService::actionPointOptions($resumeType);
        $leftNavigationLinks = NavigationLinkService::leftNavigationLinks(
            $resumeType,
            false,
            $companySlug,
            $jobPostingSlug
        );

        // show the view file!
        return view('pages.app', compact(
            'resume',
            'leftNavigationLinks',
            'slug',
            'rightNavigationLinks',
            'actionPointOptions',
            'questionnaire'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Resume $resume)
    {
        // do nothing
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

        // save companySlug
        $companySlug = $company->slug;

        // check if user is updating or creating a new answer
        $jobQuestionnaireOrAnswer = JobQuestionnaireAnswer::findForJob($companySlug, $jobPostingSlug);

        // validate the request
        Validator::make($request->all(), [
            'sections' => 'required',
        ])->after(function ($validator) use ($request) {
            $missingAnswer = false;

            // go through all sections, get bullet-section, and make sure all have answers
            foreach ($request->sections as $section) {
                if ($section['question'] ?? false) {
                    if (isset($section['data']) && !empty($section['data'])) {
                        foreach ($section['data'] as $question) {
                            if (isset($question['type']) && ($question['type'] == 'magis-string' || $question['type'] == 'bullet-radio-question')) {
                                if (empty($question['answer'])) {
                                    $missingAnswer = true;
                                    break 2;
                                }
                            }
                        }
                    }
                }
            }

            if ($missingAnswer) {
                $validator->errors()->add('sections', 'Please answer all fields');
            }
        })->validate();

        $cleansed = (new JobQuestionnaireAnswerCleansingService)->cleanse($request->all());
        if (get_class($jobQuestionnaireOrAnswer) == JobQuestionnaireAnswer::class) {
            // check if person is authorized to update
            $this->authorize('update', $jobQuestionnaireOrAnswer);

            // update!
            $jobQuestionnaireOrAnswer->update($cleansed);
        } else {
            // check if person is authorized to create
            $this->authorize('create', JobQuestionnaireAnswer::class);

            // create!
            $newJobQuestionnaireAnswer = new JobQuestionnaireAnswer;
            $newJobQuestionnaireAnswer->companySlug = $companySlug;
            $newJobQuestionnaireAnswer->jobPostingSlug = $jobPostingSlug;
            $newJobQuestionnaireAnswer->user_id = Auth::user()->id;
            $newJobQuestionnaireAnswer->sections = $cleansed['sections'];
            $newJobQuestionnaireAnswer->save();

            event(new TrackedModelCreated($newJobQuestionnaireAnswer, JobQuestionnaireAnswer::class));
        }

        // respond success
        return [
            'status' => 'success',
            'cleansed' => $cleansed,
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Resume $resume)
    {
        //
    }
}
