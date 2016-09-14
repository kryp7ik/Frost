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
     * @var bool
     */
    public $reverse;

    /**
     * OrderCompleted constructor.
     * $reverse is set to true when an Order was complete but a payment was deleted causing the order to be open once again
     * If the reverse parameter is set to true then listeners will add stock back to inventory and customers will lose their gained points
     * @param ShopOrder $order
     * @param bool $reverse
     */
    public function __construct(ShopOrder $order, $reverse = false)
    {
        $this->order = $order;
        $this->reverse = $reverse;
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
