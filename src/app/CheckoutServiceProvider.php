<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

use Illuminate\Support\ServiceProvider;

class CheckoutServiceProvider extends ServiceProvider {

    public function register() {

        $this->app->make('MatheusFS\LaravelCheckoutPagarMe\CheckoutFacade');
    }
    
    public function boot() {
        
        $this->loadViewsFrom(dirname(__DIR__).'/views', 'checkout');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
