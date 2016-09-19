<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendInventoryCountAlertEmail extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * @var array ['name' => $instance->product->name, 'expected' => (int), 'actual' => (int)]
     */
    protected $alertStack;

    /**
     * SendInventoryAlertEmail constructor.
     * @param array $alertStack
     */
    public function __construct($alertStack)
    {
        $this->alertStack = $alertStack;
    }

    /**
     * Send email to admins
     */
    public function handle()
    {
        Mail::send('emails.inventory-count-alert', ['alerts' => $this->alertStack], function ($m) {
            $m->from('alerts@joltvapor.com', 'Jolt Vapor Alert');
            $m->to('cam@joltvapor.com')->cc('josh@joltvapor.com')->cc('joe@joltvapor.com');
            $m->subject('Inventory Count Alert');
        });
    }
}
