<?php

namespace App\Events;

use App\Models\Claim;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClaimEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $claim;
    /**
     * @var null
     */
    public $type, $model;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Claim $claim, $type = null, $model = null)
    {
        $this->claim = $claim;
        $this->type = $type;
        $this->model = $model;

    }

//    /**
//     * Get the channels the event should broadcast on.
//     *
//     * @return \Illuminate\Broadcasting\Channel|array
//     */
//    public function broadcastOn()
//    {
//        return new PrivateChannel('channel-name');
//    }
}
