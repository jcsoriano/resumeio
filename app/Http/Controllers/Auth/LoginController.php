<?php

namespace App\Http\Controllers\Auth;

use App\JobApplication;
use Illuminate\Http\Request;
use App\JobApplicationDashboard;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return redirect('/?intent=login');
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        if (!$user->verified) {
            return [
                'status' => 'not-yet-verified',
                'slug' => url($user->resumes()->first()->slug . '/?intent=verify'),
            ];
        }

        if ($request->has('apply_to')) {
            // check if it's from the signup-to-apply path, then create a job application
            if (isset($request->apply_to) && !empty($request->apply_to)) {
                list($companySlug, $jobPostingSlug) = explode('/', $request->apply_to);

                if (JobApplicationDashboard::hasAppliedTo($companySlug, $jobPostingSlug)) {
                    return [
                        'status' => 'already-applied',
                    ];
                }
                if (JobApplication::submitFor($companySlug, $jobPostingSlug, $user->id)) {
                    return [
                        'status' => 'success',
                        'hasQuestionnaire' => true,
                    ];
                }
            }
        }

        if ($request->expectsJson()) {
            return [
                'status' => 'success',
            ];
        }
    }

    /**
     * Get the failed login response instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                $this->username() => [Lang::get('auth.failed')],
            ], 422);
        }
        return redirect()->back()
            ->withInput($request->only($this->username(), 'remember'))
            ->withErrors([
                $this->username() => Lang::get('auth.failed'),
            ]);
    }
}
