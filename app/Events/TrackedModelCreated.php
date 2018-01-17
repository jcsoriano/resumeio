<?php

namespace App\Events;

use App\Magis\NotificationList;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TrackedModelCreated implements ShouldBroadcast
{
    public $model;
    public $class;

    use InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($model, $class)
    {
        $this->model = $model;
        $this->class = $class;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        return NotificationList::findBySlug('registered')->channels();
    }
}
