<?php

namespace App\Http\Controllers;

use App\Company;
use App\JobQuestionnaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\JobQuestionnaireCleansingService;

class JobPostingAttachmentController extends Controller
{
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
    public function create(Company $company, $jobPostingSlug)
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Company $company, $jobPostingSlug)
    {
        //
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
        // make sure logged-in
        $this->middleware('auth');

        // store companySlug to a variable
        $companySlug = $company->slug;

        // make sure authorized to create
        $this->authorize('create', $this->model);

        // fetch attachment
        $jobAttachment = $this->model::findOrCreateForJob($companySlug, $jobPostingSlug);

        // make sure authorized to update
        $this->authorize('update', $jobAttachment);

        // update the attachment
        $jobAttachment->update((new $this->cleansingService)->cleanse($request->all()));

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
