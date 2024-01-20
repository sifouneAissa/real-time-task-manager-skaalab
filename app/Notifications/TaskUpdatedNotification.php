<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdatedNotification extends Notification
{
    use Queueable;
    public $code ;
    public $task ;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($code,$task = null)
    {
        //
        $this->code = $code;
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['broadcast'];
    }

    /**
     * Get the broadcastable representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return BroadcastMessage
     */

    public function toBroadcast($notifiable)
    {
        $data = ['code'=> $this->code];
        if($this->task) $data['task'] = $this->task;

        return new BroadcastMessage($data);
    }
}
