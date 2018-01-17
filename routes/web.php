<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Auth::routes();

Route::get('home', 'Magis\UtilityController@root')->middleware('auth');
Route::get('logout', 'Magis\UtilityController@logout');
Route::get('/', 'AppController@index')->middleware('guest');
Route::post('upload/{photo?}/{visibility?}/{name?}', 'Magis\UtilityController@upload');

// verify email
Route::get('verify/{user}/{verification_code}', 'EmailVerificationController@verify');

// resend verification link
Route::post('verify', 'EmailVerificationController@resend');

// dashboard
Route::get('dashboard', 'MetricsDashboardController@show')->middleware('auth');
Route::post('dashboard', 'MetricsDashboardController@snapshot')->middleware('auth');

// Route::get('fix-urls', 'AppController@fixUrls')->middleware('auth');
// 
// Route::get('fix-rating-question-bug', 'AppController@fixRatingQuestionBug')->middleware('auth');

Route::get('user', 'Magis\UtilityController@user')->middleware('auth');

Route::get('company', 'AppController@company');
Route::get('job', 'AppController@job');
Route::get('formbuilder', 'AppController@formbuilder');

// Route::resource('resumes', 'ResumeController', ['except' => ['create', 'store', 'edit', 'destroy', 'show', 'index']]);

// Route::get('verify-reminder', 'AppController@formbuilder');
// Route::get('email-verify/{email}/{token}', 'Magis\EmailVerificationController@attempt');

Route::get('contact', 'Magis\ContactController@show');
Route::get('contact/manage', 'Magis\UtilityController@root');
Route::post('contact', 'Magis\ContactController@send');


Route::get('typeMap', 'Magis\UtilityController@typeMap');
Route::get('test', 'Magis\UtilityController@test');
Route::post('gallery', 'Magis\UtilityController@gallery');
Route::get('sessions', 'Magis\UtilityController@sessions');

Route::get('auth/{provider}', 'Magis\SocialAuthController@redirectToProvider');
Route::get('auth/{provider}/callback', 'Magis\SocialAuthController@handleProviderCallback');

Route::post('settings', 'Magis\SettingsController@save')->middleware('auth');
Route::get('settings', 'Magis\SettingsController@list')->middleware('auth');
Route::get('activities', 'Magis\ActivityController@list')->middleware('auth');

Route::get('roles/manage', 'Magis\RoleController@admin');
Route::get('roles/{revisionid}/revert', 'Magis\RoleController@revision');
Route::get('roles/archives', 'Magis\RoleController@archives');
Route::post('roles/restore/{key}', 'Magis\RoleController@restore');
Route::resource('roles', 'Magis\RoleController');

// Route::get('users/change-password', 'Magis\UserController@showChangePasswordForm');
// Route::put('users/change-password', 'Magis\UserController@attemptChangePassword');
Route::get('profile', 'Magis\UserController@profile');
Route::post('users/gallery', 'Magis\UserController@gallery');
Route::get('users/manage', 'Magis\UserController@admin');
Route::get('users/archives', 'Magis\UserController@archives');
Route::post('users/restore/{key}', 'Magis\UserController@restore');
Route::resource('users', 'Magis\UserController');

Route::get('notification_lists/manage', 'Magis\NotificationListController@admin');
Route::get('notification_lists/{revisionid}/revert', 'Magis\NotificationListController@revision');
Route::get('notification_lists/restore', 'Magis\NotificationListController@archives');
Route::post('notification_lists/restore/{key}', 'Magis\NotificationListController@restore');
Route::resource('notification_lists', 'Magis\NotificationListController');

Route::get('menus/manage', 'Magis\MenuController@admin');
Route::get('menus/{revisionid}/revert', 'Magis\MenuController@revision');
Route::get('menus/restore', 'Magis\MenuController@archives');
Route::post('menus/restore/{key}', 'Magis\MenuController@restore');
Route::resource('menus', 'Magis\MenuController');

// view a resume
Route::get('{resume}', 'ResumeController@show');

// view a job posting
Route::get('{company}/{job}', 'JobPostingController@show');

// save changes to the resume
Route::put('{resume}', 'ResumeController@update');

// create a job posting
Route::post('job_postings', 'JobPostingController@store');

// save changes to the job posting
Route::put('{company}/{job}', 'JobPostingController@update');

// apply to a job posting
Route::post('{company}/{job}', 'JobApplicationController@store');

// save job application dashboard
Route::put('{company}/{job}/dashboard', 'JobApplicationDashboardController@update');

// create an online questionnaire for the job
Route::put('{company}/{job}/questionnaire', 'JobQuestionnaireController@update');

// answer the online questionnaire
Route::get('{company}/{job}/apply', 'JobQuestionnaireAnswerController@show');

// update the online questionnaire
Route::put('{company}/{job}/apply', 'JobQuestionnaireAnswerController@update');

// send a message to owner of resume
Route::post('{resume}', 'ContactController@send');

// view answers to online questionnaire
Route::get('{company}/{job}/{user_id?}', 'JobQuestionnaireAnswerController@show');
