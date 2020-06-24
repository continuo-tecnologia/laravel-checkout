<?php

namespace MatheusFS\LaravelCheckout;

use Illuminate\Support\ServiceProvider;

class CheckoutServiceProvider extends ServiceProvider {

    public function register() {

        $this->app->make('MatheusFS\LaravelCheckout\Checkout');
    }
    
    public function boot() {
        
        $this->loadViewsFrom(__DIR__.'/src/views', 'checkout');
        $this->loadRoutesFrom(__DIR__.'/src/app/routes.php');
    }
}
