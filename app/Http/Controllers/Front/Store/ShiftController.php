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

    /**
     * Returns json array of all shifts in given time frame
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $shifts = $this->shiftRepo->getAll($request->input('start'), $request->input('end'), true);
        return response()->json($shifts);
    }

    /**
     * Handles ajax POST request to create a new Shift
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $shift = $this->shiftRepo->create($request->all());
        return response()->json($shift);
    }

    /**
     * Handles ajax request to update a shift
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $shift = $this->shiftRepo->update($id, $request->all());
        return response()->json($shift);
    }

    /**
     * Handles ajax request to delete a shift
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->shiftRepo->delete($id);
        return response()->json('Success');
    }

    /**
     * Handles clock in/out functionality
     */
    public function clock()
    {
        $ret = $this->shiftRepo->clock(Auth::user()->id);
        return response()->json($ret);
    }

}