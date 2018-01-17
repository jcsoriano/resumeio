<?php

namespace App\Services;

use App\JobQuestionnaire;
use Illuminate\Support\Facades\Auth;

class NavigationLinkService
{
    public static function rightNavigationLinks($canEdit = false)
    {
        if (Auth::check()) {
            $rightNavigationLinks = [];

            if (!$canEdit) {
                $rightNavigationLinks[] = [
                    'type' => 'link',
                    'name' => 'Home',
                    'link' => url('home'),
                ];
            }

            $rightNavigationLinks[] = [
                'name' => 'Log-out',
                'type' => 'link',
                'link' => url('logout'),
            ];

            return $rightNavigationLinks;
        }

        return [
            [
                'name' => 'CREATE your own online resume FREE!',
                'type' => 'component',
                'component' => 'sign-up',
            ],
            [
                'name' => 'Log-in',
                'type' => 'component',
                'component' => 'log-in',
            ],
        ];
    }

    public static function actionPointOptions($resumeType, $canEdit = false, $alreadyApplied = false, $ownerVerified = false)
    {
        if ($canEdit) {
            return [
                'text' => 'Save Changes',
                'type' => 'event',
                'event' => 'save',
                'color' => 'green',
            ];
        } elseif ($resumeType == 'job_questionnaire_answer') {
            if (Auth::user()->is_company) {
                return [
                    'text' => 'View Resume',
                    'type' => 'show',
                    'color' => 'green',
                    'component' => 'sections',
                    'slug' => 'resume',
                    'valueProp' => 'resume',
                    'back' => [
                        'text' => 'Back to Interview Answers',
                        'slug' => 'main',
                    ],
                ];
            }

            return [
                'text' => 'Submit Answers',
                'type' => 'event',
                'event' => 'save',
                'color' => 'red',
            ];
        } elseif ($resumeType == 'resume' && $ownerVerified) {
            return [
                'text' => 'Contact',
                'type' => 'component',
                'color' => 'green',
                'component' => 'contact',
            ];
        } elseif ($resumeType == 'job_posting') {
            if (Auth::check() && Auth::user()->is_company) {
                return [
                    'text' => 'Apply Now!',
                    'type' => 'notice',
                    'notice' => 'Companies cannot Apply to Job Postings',
                    'color' => 'red',
                ];
            }

            if ($alreadyApplied) {
                return [
                    'text' => 'Applied',
                    'type' => 'notice',
                    'notice' => 'You have already applied to this Job Posting',
                    'color' => 'red',
                ];
            }

            return [
                'text' => 'Apply Now!',
                'type' => 'event',
                'event' => 'apply',
                'color' => 'green',
            ];
        } elseif ($resumeType == 'company') {
            return [
                'text' => 'View Job Postings',
                'type' => 'show',
                'color' => 'orange',
                'component' => 'job-postings',
                'slug' => 'job-postings',
                'valueProp' => 'jobPostings',
                'back' => [
                    'text' => 'Company Profile',
                    'slug' => 'main',
                ],
            ];
        } else {
            return [
                'text' => 'Sign-up For Free!',
                'type' => 'component',
                'color' => 'purple',
                'component' => 'sign-up',
            ];
        }
    }

    public static function leftNavigationLinks($resumeType, $canEdit = false, $companySlug = '', $jobPostingSlug = '', $hasApplied = false)
    {
        $leftNavigationLinks = [];

        if (!Auth::check()) {
            $leftNavigationLinks[] = [
                'type' => 'link',
                'name' => 'Home',
                'link' => url('/'),
            ];
        }

        if (in_array($resumeType, ['job_posting', 'job_questionnaire', 'job_questionnaire_answer'])) {
            $leftNavigationLinks[] = [
                'type' => 'link',
                'name' => 'Company Profile',
                'link' => url($companySlug),
            ];
        }

        if ($resumeType == 'job_posting') {
            if (Auth::check() && !Auth::user()->is_company && JobQuestionnaire::requiredOnApplyTo($companySlug, $jobPostingSlug) && $hasApplied) {
                $leftNavigationLinks[] = [
                    'type' => 'link',
                    'name' => 'Questionnaire',
                    'link' => url($companySlug . '/' . $jobPostingSlug . '/apply'),
                ];
            }
        }

        if ($resumeType == 'job_questionnaire' || $resumeType == 'job_questionnaire_answer') {
            $leftNavigationLinks[] = [
                'type' => 'link',
                'name' => 'Back to Job Posting',
                'link' => url($companySlug . '/' . $jobPostingSlug),
            ];

            if (Auth::user()->is_company && $resumeType == 'job_questionnaire_answer') {
                $leftNavigationLinks[] = [
                    'type' => 'show',
                    'name' => 'View Resume',
                    'component' => 'sections',
                    'slug' => 'resume',
                    'valueProp' => 'resume',
                    'back' => [
                        'name' => 'Interview Answers',
                        'slug' => 'main',
                    ],
                ];
            }
        } elseif ($resumeType == 'company') {
            $leftNavigationLinks[] = [
                'type' => 'show',
                'name' => 'Job Postings',
                'component' => 'job-postings',
                'slug' => 'job-postings',
                'valueProp' => 'jobPostings',
                'back' => [
                    'name' => 'Company Profile',
                    'slug' => 'main',
                ],
            ];
        }

        if (Auth::check() && Auth::user()->is_company) {
            $leftNavigationLinks[] = [
                'type' => 'component',
                'name' => 'Create Job Posting (FREE!)',
                'component' => 'create-job-posting',
            ];

            if ($resumeType == 'job_posting' && $canEdit) {
                $leftNavigationLinks[] = [
                    'type' => 'show',
                    'name' => 'Dashboard',
                    'component' => 'job-application-dashboard',
                    'slug' => 'job-application-dashboard',
                    'valueProp' => 'jobApplicationDashboard',
                    'syncUrl' => url()->current() . '/dashboard',
                    'back' => [
                        'name' => 'Job Page',
                        'slug' => 'main',
                    ],
                ];
                $leftNavigationLinks[] = [
                    'type' => 'show',
                    'name' => 'Questionnaire',
                    'component' => 'questionnaire-builder',
                    'slug' => 'questionnaire-builder',
                    'valueProp' => 'jobQuestionnaire',
                    'syncUrl' => url()->current() . '/questionnaire',
                    'back' => [
                        'name' => 'Job Page',
                        'slug' => 'main',
                    ],
                ];
            }
        }

        return $leftNavigationLinks;
    }
}
