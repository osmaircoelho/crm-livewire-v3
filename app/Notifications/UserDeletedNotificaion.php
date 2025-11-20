<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserDeletedNotificaion extends Notification
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
                    ->line('You no longer have access to the ' . config('app.name'))

                    ->line('Thank you for using our application! 🖖🏽');
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
