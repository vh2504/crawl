<?php

namespace App\Providers;

use App\Providers\RegisterSuccess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailConfirm
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
     * @param  RegisterSuccess  $event
     * @return void
     */
    public function handle(RegisterSuccess $event)
    {
        //
    }
}
