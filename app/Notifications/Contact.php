<?php

namespace App\Notifications;

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
                        ->subject('[My Resumes] Message from '.$message['name'])
                        ->greeting($message['message']);
        
        if (isset($message['url']) && !empty($message['url'])) {
            $mailMessage->action('View '.$message['name'].'\'s Profile', $message['url']);
        }

        $mailMessage->line('You may reply to the sender by replying to this email');
        
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
