<?php

namespace App\Services\Store;

use App\Models\Store\ProductInstance;
use App\Models\Store\Shipment;
use App\Models\Store\ShopOrder;
use App\Models\Store\Transfer;
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

    /**
     * If an order that was complete had a payment removed thus causing the order to be open again add the orders productInstances
     * quantities back to their respective stock.  If the order is then completed they will be removed from stock again.
     * @param ShopOrder $order
     */
    public function reverseAdjustInventoryForOrder(ShopOrder $order)
    {
        foreach ($order->productInstances as $instance) {
            $this->productRepo->updateStock($instance, $instance->pivot->quantity);
        }
    }

    /**
     * Adds the amount of a ProductInstance received in a given shipment to the ProductInstance's stock
     * @param Shipment $shipment
     */
    public function adjustInventoryForShipment(Shipment $shipment)
    {
        foreach ($shipment->productInstances as $instance) {
            $this->productRepo->updateStock($instance, $instance->pivot->quantity);
        }
    }

    /**
     * Transfers each ProductInstance stock from one instance to the other related one belonging to a different store
     * @param Transfer $transfer
     * @return bool
     */
    public function adjustInventoryForTransfer(Transfer $transfer)
    {
        foreach ($transfer->productInstances as $instance) {
            $related = $this->productRepo->findRelatedForStore($instance, $transfer->to_store);
            if ($related instanceof ProductInstance) {
                $this->productRepo->updateStock($related, $instance->pivot->quantity);
                $this->productRepo->updateStock($instance, -($instance->pivot->quantity));
            } else {
                flash('Could not find the product instance for the receiving store.  Please ensure you have an instance
                for all the products that belongs to your store and is active', 'danger');
                return false;
            }
        }
        return true;
    }
}