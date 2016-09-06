<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Store\ShopOrder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderCompleted extends Event
{
    use SerializesModels;

    /**
     * @var ShopOrder
     */
    public $order;

    /**
     * OrderCompleted constructor.
     * @param ShopOrder $order
     */
    public function __construct(ShopOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
