<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SomeoneApplied extends Notification implements ShouldQueue
{
    private $company;
    private $jobPostingSlug;
    private $applicant;

    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($company, $jobPostingSlug, $applicant)
    {
        $this->company = $company;
        $this->jobPostingSlug = $jobPostingSlug;
        $this->applicant = $applicant;
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
        $jobPostingName = $this->company->findJobPostingOrFail($this->jobPostingSlug)->profile['name'];
        return (new MailMessage)
            ->subject('[My Resumes] ' . $this->applicant->name
                . ' applied to ' . $jobPostingName)
            ->greeting('Hello!')
            ->line('Someone applied to your Job Posting: ' . $jobPostingName)
            ->action('View Resume', url($this->applicant->resumes()->first()->slug))
            ->line('Happy hiring!');
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
