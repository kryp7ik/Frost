<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LiquidProductDeleted extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $liquid;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($liquid)
    {
        $this->liquid = [
            'id' => $liquid->id,
            'recipe_id' => $liquid->recipe->id,
            'recipe' => $liquid->recipe->name,
            'store' => $liquid->store,
            'size' => $liquid->size,
            'shop_order_id' => $liquid->shopOrder->id,
            'nicotine' => $liquid->nicotine,
            'extra' => $liquid->extra,
            'menthol' => $liquid->menthol,
            'vg' => $liquid->vg,
        ];
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['touch'];
    }
}
