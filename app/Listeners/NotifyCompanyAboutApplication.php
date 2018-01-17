<?php

namespace App\Listeners;

use App\Events\Applied;
use App\Notifications\SomeoneApplied;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyCompanyAboutApplication implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Applied  $event
     * @return void
     */
    public function handle(Applied $event)
    {
        $event->company->user->notify(new SomeoneApplied($event->company, $event->jobPostingSlug, $event->applicant));
    }
}
