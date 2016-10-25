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
        $shifts = $this->shiftRepo->getAll($request->input('start'), $request->input('end'), true);
        return response()->json($shifts);
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        $shift = $this->shiftRepo->create($request->all());
        return response()->json($shift);
    }

    public function show()
    {

    }

    public function edit()
    {

    }

    public function update($id, Request $request)
    {
        $shift = $this->shiftRepo->update($id, $request->all());
        return response()->json($shift);
    }

    public function destroy()
    {

    }

}