<?php

namespace App\Services\Store;

use App\Models\Store\ShopOrder;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;

class InventoryService {

    /**
     * @var ProductInstanceRepositoryContract
     */
    protected $productRepo;

    /**
     * InventoryService constructor.
     * @param ProductInstanceRepositoryContract $productRepo
     */
    public function __construct(ProductInstanceRepositoryContract $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /**
     * Subtracts the quantity of each ProductInstance attached to the order from the products stock
     * @param ShopOrder $order
     * @return bool
     */
    public function adjustInventoryForOrder(ShopOrder $order)
    {
        foreach ($order->productInstances as $instance) {
            $this->productRepo->updateStock($instance, -($instance->pivot->quantity));
        }
    }
}