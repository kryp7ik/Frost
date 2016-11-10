<?php

namespace App\Http\Controllers\Admin\Store;

use App\Repositories\Store\LiquidProduct\LiquidProductRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class TouchController extends Controller
{

    /**
     * Display view only
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function touch()
    {
        return view('backend.store.touch.touch');
    }

    /**
     * Retrieves all LiquidProducts for the active store and returns as json array
     * @param LiquidProductRepositoryContract $liquidRepo
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLiquids(LiquidProductRepositoryContract $liquidRepo)
    {
        $liquids = $liquidRepo->getIncompleteWhereStore(Auth::user()->store, true);
        return response()->json($liquids);
    }

    /**
     * Used for ajax request to complete LiquidProducts
     * @param Request $request
     * @param LiquidProductRepositoryContract $liquidRepo
     */
    public function complete(Request $request, LiquidProductRepositoryContract $liquidRepo)
    {
        foreach ($request->all() as $liquidProductId) {
            $liquidRepo->updateField($liquidProductId, 'mixed', 1);
        }
    }

}