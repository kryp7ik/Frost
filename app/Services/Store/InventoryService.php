<?php

namespace App\Services\Store;

use App\Models\Store\ProductInstance;
use App\Models\Store\Shipment;
use App\Models\Store\ShopOrder;
use App\Models\Store\Transfer;
use App\Repositories\Store\ProductInstance\ProductInstanceRepositoryContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $this->productRepo->adjustStock($instance, -($instance->pivot->quantity));
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
            $this->productRepo->adjustStock($instance, $instance->pivot->quantity);
        }
    }

    /**
     * Adds the amount of a ProductInstance received in a given shipment to the ProductInstance's stock
     * @param Shipment $shipment
     */
    public function adjustInventoryForShipment(Shipment $shipment)
    {
        foreach ($shipment->productInstances as $instance) {
            $this->productRepo->adjustStock($instance, $instance->pivot->quantity);
        }
        flash('Your inventory has been updated successfully', 'success');
    }

    /**
     * Transfers each ProductInstance stock from one instance to the other related one belonging to a different store
     * @param Transfer $transfer
     * @return bool
     */
    public function adjustInventoryForTransfer(Transfer $transfer)
    {
        $error = false;
        foreach ($transfer->productInstances as $instance) {
            if ($instance->pivot->received) continue;
            $related = $this->productRepo->findRelatedForStore($instance, $transfer->to_store);
            if ($related instanceof ProductInstance) {
                $this->productRepo->adjustStock($related, $instance->pivot->quantity);
                $this->productRepo->adjustStock($instance, -($instance->pivot->quantity));
                DB::table('instance_transfer')->where('id', $instance->pivot->id)->update(['received' => true]);
            } else {
                $error = true;
            }
        }
        if ($error) {
            flash('Some products were unable to transfer.  Please ensure you have an active instance for each product in the transfer and then click receive again to complete the transfer.', 'danger');
            return false;
        } else {
            return true;
        }
    }

    /**
     * Updates the stock of each ProductInstance in the $data array and returns an array of discrepancies
     * @param array $data POST data from the Inventory Count form
     * @return array $alertStack An array of ProductInstances where the count entered does not equal the expected stock
     */
    public function processInventoryCount($data)
    {
        $alertStack['store'] = config('store.stores')[Auth::user()->store];
        $alertStack['products'] = [];
        foreach ($data['products'] as $fieldsetData) {
            if ($fieldsetData['instance'] == 0) continue;
            $instance = $this->productRepo->findById($fieldsetData['instance']);
            if($instance->stock != $fieldsetData['quantity']){
                $alertStack['products'][] = ['name' => $instance->product->name, 'expected' => $instance->stock, 'actual' => $fieldsetData['quantity']];
                $this->productRepo->adjustStock($instance, $fieldsetData['quantity'], true);
            }
        }
        flash('Your inventory has been updated successfully', 'success');
        return $alertStack;
    }
}