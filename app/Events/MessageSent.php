<?php

namespace App\Events;

use App\Events\Event;
use App\Models\Messenger\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class MessageSent extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $message;

    /**
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['raven'];
    }
}
