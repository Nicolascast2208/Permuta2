<?php

namespace App\Providers;

use App\Events\ProductCreated;
use App\Events\OfferReceived;
use App\Events\TradeAccepted;
use App\Listeners\CreateProductNotification;
use App\Listeners\CreateOfferNotification;
use App\Listeners\CreateTradeNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ProductCreated::class => [
            CreateProductNotification::class,
        ],
        OfferReceived::class => [
            CreateOfferNotification::class,
        ],
        TradeAccepted::class => [
            CreateTradeNotification::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}