<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class WooCommerce extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'woocommerce';
    }
}