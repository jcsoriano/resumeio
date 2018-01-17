<?php

namespace App\Http\Controllers\Magis;

use App;
use App\Http\Requests;
use App\Magis\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Magis\Services\ActivityService;
use Illuminate\Support\Facades\Redirect;

class ActivityController extends Controller
{
    

    public function list()
    {
        if (request()->expectsJson()) {
            return [
                'settings' => ActivityService::recent()
            ];
        }
        return [
                'settings' => ActivityService::recent()
            ];

        // return App::abort(404);
    }
}
