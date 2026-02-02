<?php

namespace App\Listeners;

use App\Events\OfferCreated;
use App\Mail\OfferReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendOfferNotification implements ShouldQueue
{
    public function handle(OfferCreated $event)
    {
        Mail::to($event->offer->toUser->email)
            ->send(new OfferReceived($event->offer));
    }
}