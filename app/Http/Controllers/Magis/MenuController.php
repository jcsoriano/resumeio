<?php

namespace App\Http\Controllers\Magis;

use App\Http\Requests;
use App\Magis\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Magis\MagisController;

class MenuController extends MagisController
{
    protected $model = Menu::class;
}
