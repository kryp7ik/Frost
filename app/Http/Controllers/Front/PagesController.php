<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests;

class PagesController extends Controller
{
    public function home()
    {
        return view('home');
    }

}
