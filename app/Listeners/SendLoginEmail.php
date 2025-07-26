<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;
use App\Mail\LoginEmail;
use Illuminate\Support\Facades\Mail;

class SendLoginEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        Mail::to($event->user)->send(new LoginEmail($event->user));
    }
}
