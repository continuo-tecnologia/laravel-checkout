<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

use Illuminate\Support\ServiceProvider;

class CheckoutServiceProvider extends ServiceProvider {

    public function register() {

        $this->app->make('MatheusFS\LaravelCheckoutPagarMe\CheckoutFacade');
        $this->loadViewsFrom(dirname(__DIR__).'/views', 'checkout');
    }
    
    public function boot() {
        
        include __DIR__.'/routes.php';
    }
}
