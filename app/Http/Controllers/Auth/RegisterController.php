<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Resume;
use App\JobApplication;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'bail|required|email|max:255|unique:users',
            'is_company' => 'required',
            'slug' => 'bail|required|alpha_dash|max:255|unique:mongodb.resumes',
            'password' => 'required|min:6',
            'agree' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // register the user
        $user = new User;
        $user->name = $data['name'] ?? $data['email'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->is_company = isset($data['is_company']) ? $data['is_company'] : 0;
        $user->verification_code = str_random(60);
        $user->save();
        $user->fresh();

        // create resume with placeholders
        // fetch the placeholders
        $sampleResume = Resume::findBySlug($user->is_company ? 'sample-company' : 'sample-resume');

        // create the resume
        $resume = new Resume;
        $resume->type = $user->is_company ? 'company' : 'resume';
        $resume->user_id = $user->id;
        $resume->slug = $data['slug'];
        $resume->profile = [
            'name' => $user->name,
            'position' => $user->is_company ? 'Do More, Change Lives' : 'Current Position',
        ];
        
        // assign the placeholders
        $resume->leftSnapshot = $sampleResume->leftSnapshot;
        $resume->rightSnapshot = $sampleResume->rightSnapshot;
        $resume->sections = $sampleResume->sections;
        
        $resume->save();
        
        return $user;
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        // check if it's from the signup-to-apply path, then create a job application
        if ($request->has('apply_to') && !empty($request->input('apply_to'))) {
            list($companySlug, $jobPostingSlug) = explode('/', $request->apply_to);
            if (JobApplication::submitFor($companySlug, $jobPostingSlug, $user->id, $request->slug)) {
                return [
                    'status' => 'success',
                    'slug' => $request->apply_to . '/apply?introModal=from-sign-up',
                ];
            }
        }
        $introModal = $request->has('apply_to') ? 'applying' : ($user->is_company ? 'welcome-company' : 'welcome');
        return [
            'status' => 'success',
            'slug' => $request->slug . '/?introModal=' . $introModal,
        ];
    }
}
