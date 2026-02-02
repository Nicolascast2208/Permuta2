<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Transbank\Webpay\WebpayPlus;
use Transbank\Webpay\Options;

class WebpayServiceProvider extends ServiceProvider
{
    public function boot()
{
    // Configuración para diferentes entornos
    if (config('app.env') === 'production') {
        $options = new Options(
            config('services.webpay.commerce_code'),
            config('services.webpay.api_key'),
            Options::ENVIRONMENT_PRODUCTION
        );
    } else {
        // Usar configuración de integración (pruebas)
        $options = new Options(
            config('services.webpay.test_commerce_code'),
            config('services.webpay.test_api_key'),
            Options::ENVIRONMENT_INTEGRATION
        );
    }

        WebpayPlus::configure($options);
    }
}