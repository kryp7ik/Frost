<?php

namespace App\Http\Controllers\Admin\Store;

use App\Repositories\Store\LiquidProduct\LiquidProductRepositoryContract;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class TouchController extends Controller
{

    protected $liquidRepo;

    public function __construct(LiquidProductRepositoryContract $liquidRepo)
    {
        $this->liquidRepo = $liquidRepo;
    }

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function getLiquids()
    {
        $liquids = $this->liquidRepo->getIncompleteWhereStore(Auth::user()->store, true);
        return response()->json($liquids);
    }

    /**
     * Retrieves the 5 recently completed orders
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMixed()
    {
        $liquids = $this->liquidRepo->getRecentlyCompletedWhereStore(Auth::user()->store);
        return response()->json($liquids);
    }

    /**
     * Used for ajax request to complete LiquidProducts
     * @param Request $request
     */
    public function complete(Request $request)
    {
        foreach ($request->all() as $liquidProductId) {
            $this->liquidRepo->updateField($liquidProductId, 'mixed', 1);
        }
    }

    /**
     * Sets the designated LiquidProducts 'mixed' attribute back to false
     * @param int $id
     */
    public function unmix($id)
    {
        if ($id) {
            $this->liquidRepo->unMixLiquidProduct($id);
        }
    }

}