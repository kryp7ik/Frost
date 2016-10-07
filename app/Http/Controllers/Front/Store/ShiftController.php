<?php

namespace App\Http\Controllers\Front\Store;

use App\Repositories\Store\Shift\ShiftRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    protected $shiftRepo;

    public function __construct(ShiftRepositoryContract $shiftRepo)
    {
        $this->shiftRepo = $shiftRepo;
    }

    public function index(Request $request)
    {
        $shifts = ['this' => 'is', 'a' => 'test'];
        return response()->json($shifts);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        //$shift = $this->shiftRepo->create($request->all());
        return response()->json($request->all());
    }

    public function show()
    {

    }

    public function edit()
    {

    }

    public function update()
    {

    }

    public function destroy()
    {

    }

}