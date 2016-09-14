<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use App\Services\Store\InventoryService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateInventory
{

    /**
     * @var InventoryService
     */
    protected $inventoryService;

    /**
     * UpdateInventory constructor.
     * @param InventoryService $inventoryService
     */
    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    /**
     * If the order is completed subtract the ordered products from inventory
     * If the reverse parameter is true then add the previously ordered products back to the inventory
     *
     * @param  OrderCompleted  $event
     * @return void
     */
    public function handle(OrderCompleted $event)
    {
        if($event->reverse) {
            $this->inventoryService->reverseAdjustInventoryForOrder($event->order);
        } else {
            $this->inventoryService->adjustInventoryForOrder($event->order);
        }
    }
}
