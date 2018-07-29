<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class SessionNotification extends Notification
{
    use Queueable;
    
    public $session_title;
    public $notificationType;
    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($session_title, $notificationType, $message)
    {
        $this->session_title = $session_title;
        $this->notificationType = $notificationType;
        $this->message = $message;
    }

     public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'session_title' => $this->session_title,
            'notificationType' => $this->notificationType,
            'message' => $this->message
        ]);
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
}
