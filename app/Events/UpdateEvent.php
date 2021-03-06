<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use App\Models\Branch;

class UpdateEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    public $userId;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($message, $orderId)
    {
        $this->message = $message;
        $this->userId = (!is_null($orderId)) ? Branch::findOrFail(Order::findOrFail($orderId)->branch_id)->user_id : NULL;
        $this->data = get_updated_counts_for_JS_update();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('food-app');
    }

    public function broadcastAs()
    {
        return 'update-event';
    }
}
