<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class PagesController extends Controller
{
    public function home(): InertiaResponse
    {
        return Inertia::render('Admin/Home');
    }
}
