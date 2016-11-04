<?php

namespace App\Http\Controllers\Admin\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TouchController extends Controller
{

    public function touch(Request $request)
    {

        return view('backend.store.touch.touch');
    }

}