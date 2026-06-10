<?php

namespace App\Providers;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if ($address = config('mail.to.address')) {
            Event::listen(MessageSending::class, function (MessageSending $event) use ($address) {
                $event->message->to($address);
            });
        }
    }
}
