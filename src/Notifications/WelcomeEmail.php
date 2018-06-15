<?php

namespace Gurinder\LaravelAuth\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class WelcomeEmail extends Notification implements ShouldQueue
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
        $fromName = config('gauth.email_from.name') ?? config('app.name');

        return (new MailMessage)
            ->from(config('gauth.email_from.email'), $fromName)
            ->subject('Welcome to ' . $fromName)
            ->greeting('Welcome ' . ucwords($notifiable->name))
            ->line('Thank you for joining ' . config('app.name'))
            ->action('Check out site', url(config('gauth.redirect_path_after_login')));

    }
}