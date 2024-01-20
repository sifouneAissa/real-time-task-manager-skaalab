<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdateEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $users;
    public $code;
    public $task;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($users,$code,$task = null)
    {
        //
        $this->users = $users;
        $this->code = $code;
        $this->task = $task;
    }

//    /**
//     * Get the channels the event should broadcast on.
//     *
//     * @return \Illuminate\Broadcasting\Channel|array
//     */
//    public function broadcastOn()
//    {
//        return new PrivateChannel('App.Models.User.1');
//    }
}
