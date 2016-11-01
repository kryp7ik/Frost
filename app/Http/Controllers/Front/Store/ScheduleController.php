<?php

namespace App\Http\Controllers\Front\Store;

use App\Repositories\Auth\UserRepositoryContract;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{

    public function home(UserRepositoryContract $userRepo)
    {
        $users = $userRepo->getAll();
        return view('schedule.home', compact('users'));
    }

    public function warning()
    {
        return view('schedule.warning');
    }

}