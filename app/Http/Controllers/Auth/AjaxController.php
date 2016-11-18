<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AjaxController extends Controller
{

    /**
     * Used for an ajax call to check if the user has the manager role
     * @return \Illuminate\Http\JsonResponse
     */
    public function status() {
        if (Auth::user()->hasRole('manager')) {
            return response()->json(['manager' => true]);
        } else {
            return response()->json(['manager' => false]);
        }
    }
}
