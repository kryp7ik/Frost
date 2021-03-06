<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Store\LiquidProduct;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class LiquidProductCreated extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $liquid;

    /**
     * LiquidProductCreated constructor.
     * @param LiquidProduct $liquid
     */
    public function __construct(LiquidProduct $liquid)
    {
        $this->liquid = [
            'id' => $liquid->id,
            'recipe_id' => $liquid->recipe->id,
            'recipe' => $liquid->recipe->name,
            'store' => $liquid->store,
            'size' => $liquid->size,
            'shop_order_id' => $liquid->shopOrder->id,
            'nicotine' => $liquid->nicotine,
            'salt' => $liquid->salt,
            'extra' => $liquid->extra,
            'menthol' => $liquid->menthol,
            'vg' => $liquid->vg,
        ];
        foreach ($liquid->recipe->ingredients as $ingredient) {
            $this->liquid['ingredients'][] = [
                'name' => $ingredient->name,
                'vendor' => $ingredient->vendor,
                'amount' => $ingredient->pivot->amount
            ];
        }
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
