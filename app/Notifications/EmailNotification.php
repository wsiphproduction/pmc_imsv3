<?php

namespace App\Notifications;

use App\Http\Controllers\Admin\LoginController;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\users;

class EmailNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    public function __construct(users $user)
    {
        $this->user = $user;
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
        $user = $this->user;
        return (new MailMessage)
            ->greeting("Hello!")
            ->line('You are enrolled in Indent Monitoring System!')
            ->line('Username : '.$user->domainAccount)
            ->line('Password : password')
            ->action('Login to your account ', url('/login'))
            ->line('Best regards!');
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
    // public function toDatabase($notifiable)
    // {
    //     return [
    //         'id' => $this->application->id,
    //         'reason' => $this->application->reason,
    //         'scheduled_date' => $this->application->scheduled_date,
    //         'scheduled_time' => $this->application->scheduled_time,
    //     ];
    // }
}
