<?php

namespace App\Listeners;

use App\Notifications\TaskUpdatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class TaskUpdateListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        try {
            Notification::send($event->users,new TaskUpdatedNotification($event->code,$event->task));
        }catch (\Exception $e){

        }
    }
}
