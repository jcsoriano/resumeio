<?php

namespace App\Magis\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Contact extends Notification implements ShouldQueue
{
    private $message;

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $message = $this->message;
        $mailMessage = (new MailMessage)
                        ->replyTo($message['email'])
                        ->subject('[Contact us] Message from '.$message['name'])
                        ->greeting($message['message'])
                        ->line('Sender Details:');
        unset($message['message']);
        foreach ($message as $label => $m) {
            $mailMessage->line(title_case(str_replace('_', ' ', $label).': '.$m));
        }
        return $mailMessage;
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
            'message' => $this->message,
        ];
    }
}
