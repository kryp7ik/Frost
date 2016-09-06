<?php

namespace App\Listeners;

use App\Events\OrderCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EarnCustomerPoints
{

    /**
     * Upon order completion if there is a customer attached earn their reward points
     *
     * @param  OrderCompleted  $event
     * @return void
     */
    public function handle(OrderCompleted $event)
    {
        if ($event->order->customer) {
            $customer = $event->order->customer;
            $customer->points += $event->order->calculator()->getPoints();
            $customer->save();
        }
    }
}
