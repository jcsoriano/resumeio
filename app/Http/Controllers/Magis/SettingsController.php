<?php

namespace App\Http\Controllers\Magis;

use App\Http\Requests;
use App\Magis\Setting;
use Illuminate\Http\Request;
use App\Magis\Services\AuthService;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Magis\Services\SettingsService;
use Illuminate\Support\Facades\Redirect;

class SettingsController extends Controller
{
    public function save(Request $request)
    {
        AuthService::authorize('settings');

        // fetch name-slug pair
        $names = Setting::all()->pluck('name', 'slug')->all();

        // delete the table (deleting and batch inserting is quicker than batch updates)
        Setting::truncate();

        // get the input values
        $newValues = $request->all();

        // insert new values
        $insert = [];
        foreach ($newValues as $setting) {
            $slug = $setting['slug'];
            $insert[] = [
                'slug' => $slug,
                'name' => $names[$slug],
                'content' => $setting['content']
            ];
        }
        Setting::insert($insert);

        // flush cache
        Cache::tags([Setting::SLUG])->flush();

        if (request()->expectsJson()) {
            return ['status' => 'success'];
        }

        return Redirect::back();
    }

    public function list()
    {
        if (request()->expectsJson()) {
            return [
                'settings' => SettingsService::get()
            ];
        }

        return App::abort(404);
    }
}
