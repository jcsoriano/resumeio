<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use App\Magis\Services\PermissionService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\JobQuestionnaireAnswer' => 'App\Policies\JobQuestionnaireAnswerPolicy',
        'App\JobQuestionnaire' => 'App\Policies\JobQuestionnairePolicy',
        'App\JobApplicationDashboard' => 'App\Policies\JobApplicationDashboardPolicy',
        'App\JobApplication' => 'App\Policies\JobApplicationPolicy',
        'App\JobPosting' => 'App\Policies\JobPostingPolicy',
        'App\Resume' => 'App\Policies\ResumePolicy',
        'App\Magis\NotificationList' => 'App\Magis\Policies\NotificationListPolicy',
        'App\User' => 'App\Magis\Policies\UserPolicy',
        'App\Magis\Role' => 'App\Magis\Policies\RolePolicy',
        'App\Magis\MenuItem' => 'App\Magis\Policies\MenuItemPolicy',
        'App\Magis\Menu' => 'App\Magis\Policies\MenuPolicy',
        'App\Magis\Setting' => 'App\Magis\Policies\SettingPolicy',
        
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
