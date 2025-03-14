<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public $password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the reset password notification.
     *
     * This method constructs a MailMessage instance that represents the
     * reset password notification. It includes the subject, greeting,
     * user email, and temporary password. It also provides an action link
     * to the login page.
     *
     * @param object $notifiable The notifiable entity that will receive the notification.
     * @return MailMessage The mail message to be sent.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(config('app.name').' | Password Reset')
            ->greeting("Greetings, {$notifiable->name}!")
            ->line('Please login to your account using the credentials provided below.')
            ->line('Email: '.$notifiable->email)
            ->line('Password: '.$this->password)
            ->action('Login Here', url(route('filament.admin.auth.login')));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
