<?php

namespace App\Http\Controllers\Front\Store;

use App\Repositories\Auth\UserRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    public function home(UserRepositoryContract $userRepo)
    {
        $users = $userRepo->getAll();
        return view('schedule.home', compact('users'));
    }

}