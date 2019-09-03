<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordRequest extends Notification
{
    use Queueable;
    protected $token;
    protected $admin;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $admin)
    {
        $this->token = $token;
        $this->admin = $admin;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = '';
        if ($this->admin) {
            $url = url('https://maxdino-dashboard.herokuapp.com/accept/reset-password/' . $this->token);
            //send link redirect to page CMS - Admin
        } else {
            $url = url('http://localhost:3000/accept/reset-password/' . $this->token);
        }

        return (new MailMessage)
            ->subject('<Maxdino> Reset password link')
            ->line('Press the link below to reset your password.')
            ->action('Reset password here', url($url))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
