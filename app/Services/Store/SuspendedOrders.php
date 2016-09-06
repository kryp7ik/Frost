<?php

namespace App\Services\Store;

use App\Repositories\Store\ShopOrder\ShopOrderRepositoryContract;
use Illuminate\Support\Facades\Auth;

class SuspendedOrders {

    protected $orderRepo;

    public function __construct(ShopOrderRepositoryContract $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function getSuspendedOrders()
    {
        return $this->orderRepo->getIncompleteForStore(Auth::user()->store);
    }
}