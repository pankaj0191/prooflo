<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InvitePepoleByLink
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $inv;

    public $project;

    public $revision;

    /**
     * Create a new event instance.
     *
     * @param $user
     * @param $project
     * @param $revision
     */
    public function __construct($inv, $project, $revision)
    {
        $this->inv = $inv;
        $this->project = $project;
        $this->revision = $revision;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
