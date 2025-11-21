<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserRestoredAccessNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
                    ->line('Your access to ' . config('app.name') . ' has been restored.')
                    ->line('Welcome back! 😃');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
