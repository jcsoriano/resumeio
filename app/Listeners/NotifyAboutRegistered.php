<?php

namespace App\Listeners;

use Carbon\Carbon;
use App\Magis\NotificationList;
use Illuminate\Auth\Events\Registered;
use App\Notifications\SomeoneRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class NotifyAboutRegistered implements ShouldQueue
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
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        Notification::send(
            NotificationList::findBySlug('registered')->users()->get(),
            (new SomeoneRegistered($event->user)) // ->delay(Carbon::now()->addMinutes(1))
        );
    }
}
