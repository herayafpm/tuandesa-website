<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class PasswordResetNotification extends Notification
{
    use Queueable;
    protected $token;
    public function __construct($token)
    {
      $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $verifyUrl = URL::temporarySignedRoute('password.reset', Carbon::now()->addMinutes(60), ['token' => $this->token,'email' => $notifiable->email]);

        return (new MailMessage)
            ->markdown('emails.password-reset', ['url' => $verifyUrl,'user' => $notifiable])
            ->subject('Password Reset '.env('APP_NAME'));
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}