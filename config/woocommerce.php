<?php

return [
    'store_url' => env('WOOCOMMERCE_STORE_URL'),
    'consumer_key' => env('WOOCOMMERCE_CONSUMER_KEY'),
    'consumer_secret' => env('WOOCOMMERCE_CONSUMER_SECRET'),
    'options' => [
        'version' => 'wc/v3',
        'verify_ssl' => env('WOOCOMMERCE_API_VERIFY_SSL', true),
    ],
];