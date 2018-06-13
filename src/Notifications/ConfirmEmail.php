<?php

namespace Gurinder\LaravelAuth\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmEmail extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct()
    {
        $this->user = config('gauth.user_model') ?? config('auth.providers.users.model');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $confirmationLink = $this->getEmailVerificationLink($notifiable);

        $fromName = config('gauth.email_from.name') ?? config('app.name');

        return (new MailMessage)
            ->from(config('gauth.email_from.email'), $fromName)
            ->subject('Confirm Email | ' . $fromName)
            ->greeting('Hello ' . ucwords($notifiable->name))
            ->action('Confirm Email', $confirmationLink)
            ->line('Please Confirm your email, by clicking above button');

    }


    protected function getEmailVerificationLink($user)
    {
        $data = [
            'email' => $user->email,
            'token' => $user->email_verification_token
        ];

        $data = encrypt(base64_encode(json_encode($data)));

        return route('email.confirmation.confirm', $data);
    }
}