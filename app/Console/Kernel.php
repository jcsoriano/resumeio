<?php

namespace App\Console;

use App\User;
use App\Resume;
use App\JobPosting;
use App\JobApplication;
use App\MetricSnapshot;
use App\JobQuestionnaire;
use App\JobQuestionnaireAnswer;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->call(function () {
            MetricSnapshot::create([
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
            ]);
        })->daily();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
