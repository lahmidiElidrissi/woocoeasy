<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Automattic\WooCommerce\Client;

class WooCommerceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('woocommerce', function ($app) {
            return new Client(
                config('woocommerce.store_url', env('WOOCOMMERCE_STORE_URL')),
                config('woocommerce.consumer_key', env('WOOCOMMERCE_CONSUMER_KEY')),
                config('woocommerce.consumer_secret', env('WOOCOMMERCE_CONSUMER_SECRET')),
                [
                    'version' => config('woocommerce.version', 'wc/v3'),
                    'verify_ssl' => config('woocommerce.verify_ssl', false),
                ]
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}