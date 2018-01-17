<?php

namespace App\Http\Controllers;

use App\Resume;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function index()
    {
        if (request()->has('intent')) {
            $intent = request()->intent;
        }
        return view('pages.landing', compact('intent'));
    }

    public function fixRatingQuestionBug()
    {
        $jqas = \App\JobQuestionnaireAnswer::findAllForJob('magissolutions', 'programmer');

        foreach ($jqas as $j) {
            $sections = [];
            foreach ($j->sections as $s) {
                if ($s['type'] == 'rating-section') {
                    $s['question'] = true;
                }
                $sections[] = $s;
            }
            $j->sections = $sections;
            $j->save();

            var_dump($j->toArray());
        }
    }

    public function fixUrls()
    {
        $find = 'http://localhost:3000:3000:3000:3000:3000/magisgit/resumeio_laravel/public';
        $replace = '//myresum.es';
        $resumes = Resume::all();
        foreach ($resumes as $r) {
            if ($r->sections) {
                $r->sections = $this->fixSectionUrls($r->sections, $find, $replace);

                if (isset($r->jobPostings) && !empty($r->jobPostings)) {
                    $newJobPostings = [];
                    foreach ($r->jobPostings as $jp) {
                        $jp['sections'] = $this->fixSectionUrls($jp['sections'], $find, $replace);
                        $newJobPostings[] = $jp;
                    }
                    $r->jobPostings = $newJobPostings;
                }

                $r->save();
                var_dump($r->toArray());
            }
        }
    }

    private function fixSectionUrls($sections, $find, $replace)
    {
        foreach ($sections as $rs) {
            if (strpos($rs['image'], $find) !== false) {
                $rs['image'] = str_replace($find, $replace, $rs['image']);
            }
            $newSections[] = $rs;
        }
        return $newSections;
    }

    // public function resume()
    // {
    //     return view('pages.app');
    // }

    public function company()
    {
        $company = Resume::findBySlug('sample-resume');
        return view('pages.company', compact('company'));;
    }

    public function job()
    {
        return view('pages.job');
    }

    public function formbuilder()
    {
        return view('pages.formbuilder');
    }
}
