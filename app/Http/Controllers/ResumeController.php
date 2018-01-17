<?php

namespace App\Http\Controllers;

use App\Resume;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\NavigationLinkService;
use App\Services\ResumeCleansingService;

class ResumeController extends Controller
{
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
    public function show(Resume $resume)
    {
        $slug = $resume->slug;
        $name = $resume->user->name;
        $resumeType = $resume->type;
        $canEdit = Auth::check() && Auth::user()->can('update', $resume);
        $rightNavigationLinks = NavigationLinkService::rightNavigationLinks($canEdit);
        $actionPointOptions = NavigationLinkService::actionPointOptions($resumeType, $canEdit, false, $resume->user->verified);
        $leftNavigationLinks = NavigationLinkService::leftNavigationLinks($resumeType);
        $isCompany = $resume->type == 'company';

        if ($canEdit) {
            $editing = true;

            if (request()->has('introModal')) {
                if (request()->introModal == 'welcome') {
                    $introModal = 'Thank you for signing-up! You\'ve done a great first step in boosting your career. Now you can start creating your resume. Once you\'re done, don\'t forget to share it to everyone!';
                } elseif (request()->introModal == 'applying') {
                    $introModal = 'Thanks for applying! You\'re one step closer to having a great career with the company. Update this resume, as this is what the company you applied to will see! Once done, you can also share the link to your online resume to other companies as well!';
                } elseif (request()->introModal == 'welcome-company') {
                    $introModal = 'You\'ve now done the first step towards modern, easy recruitment! Step two is fixing up your company profile. Once done, you can now post as may job postings as you want! Just remember to share it on all your social media channels to ensure lots of people apply!';
                } elseif (request()->introModal == 'thank-you-for-verifying') {
                    if ($isCompany) {
                        $introModal = 'Thank you for verifying your e-mail! You will now be able to create an unlimited number of job postings.';
                    } else {
                        $introModal = 'Thank you for verifying your e-mail! You will now be able to apply to companies and companies will now be able to contact you.';
                    }
                }
            }

            if (request()->has('intent')) {
                $intent = request()->intent;
            }
        }
        
        // TODO: show optimized resume version
        // TODO: Cache full responses when appropriate? (one version for logged-in not-company,
        // for can-edit, and for logged-out, and for logged-in company? [nope - csrfToken])
        return view('pages.app', compact(
            'resume',
            'leftNavigationLinks',
            'editing',
            'isCompany',
            'slug',
            'rightNavigationLinks',
            'actionPointOptions',
            'introModal',
            'name',
            'intent'
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
    public function update(Request $request, Resume $resume)
    {
        // ensure logged-in
        $this->middleware('auth');

        // make sure user is authorized
        $this->authorize('update', $resume);

        // update the resume
        $resume->update((new ResumeCleansingService)->cleanse($request->all()));

        // respond success
        return [
            'status' => Auth::user()->verified ? 'success' : 'saved-not-verified',
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
