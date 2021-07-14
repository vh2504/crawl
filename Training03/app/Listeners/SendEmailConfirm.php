<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\RegisterSuccess;
use App\Notifications\NotifyAboutRegisterSuccess;

class SendEmailConfirm
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(RegisterSuccess $event)
    {
        $user = $event->user;
        $user->remember_token = time();
        $user->save();

        $user->notify(new NotifyAboutRegisterSuccess());
    }
}
