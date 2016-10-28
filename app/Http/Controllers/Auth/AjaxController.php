<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{

    public function status() {
        if (Auth::user()->hasRole('manager')) {
            return response()->json(['manager' => true]);
        } else {
            return response()->json(['manager' => false]);
        }
    }
}
