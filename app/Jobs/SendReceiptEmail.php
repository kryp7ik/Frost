<?php

namespace App\Jobs;

use App\Jobs\Job;
use App\Models\Store\ShopOrder;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendReceiptEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var ShopOrder $order
     */
    protected $order;

    /**
     * SendReceiptEmail constructor.
     * @param ShopOrder $order
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Validate customers email and send receipt
     */
    public function handle()
    {
        if (filter_var($this->order->customer->email, FILTER_VALIDATE_EMAIL)) {
            Mail::send('emails.order-receipt', ['order' => $this->order], function ($m) {
                $m->from('support@joltvapor.com', 'Jolt Vapor Support');
                $m->to($this->order->customer->email);
                $m->subject('Jolt Vapor Receipt');
            });
        }
    }
}
