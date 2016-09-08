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
     * Handle the event.
     *
     * @param  OrderCompleted  $event
     * @return void
     */
    public function handle(OrderCompleted $event)
    {
        $this->inventoryService->adjustInventoryForOrder($event->order);
    }
}
