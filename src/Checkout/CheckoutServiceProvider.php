<?php

namespace MatheusFS\Laravel\Checkout;

use Illuminate\Support\ServiceProvider;

class CheckoutServiceProvider extends ServiceProvider {

    public function register() {

        $this->app->make('MatheusFS\Laravel\Checkout\Checkout');
    }
    
    public function boot() {
        
        $this->loadViewsFrom(dirname(__DIR__).'/views', 'checkout');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
