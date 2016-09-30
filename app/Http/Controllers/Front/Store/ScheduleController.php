<?php

namespace App\Http\Controllers\Front\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    public function home() {

        return view('schedule.home');
    }

}