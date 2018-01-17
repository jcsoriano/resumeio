<?php

namespace App\Http\Controllers\Magis;

use App\Http\Requests;
use App\Magis\NotificationList;
use Illuminate\Http\Request;
use App\Http\Controllers\Magis\MagisController;

class NotificationListController extends MagisController
{
    protected $model = NotificationList::class;
}
