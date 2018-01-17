<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class Applied
{
    public $company;
    public $jobPostingSlug;
    public $applicant;

    use InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($company, $jobPostingSlug, $applicant)
    {
        $this->company = $company;
        $this->jobPostingSlug = $jobPostingSlug;
        $this->applicant = $applicant;
    }
}
