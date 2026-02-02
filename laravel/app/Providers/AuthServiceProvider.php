<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\Offer;
use App\Models\Product;
use App\Policies\ChatPolicy;
use App\Policies\OfferPolicy;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Product::class => ProductPolicy::class,
        Offer::class => OfferPolicy::class,
        Chat::class => ChatPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}