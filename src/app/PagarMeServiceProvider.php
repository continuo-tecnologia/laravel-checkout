<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

use Illuminate\Support\ServiceProvider;

class PagarMeServiceProvider extends ServiceProvider {

    public function register() {

        $this->app->make('MatheusFS\LaravelCheckoutPagarMe\Facade');
        $this->loadViewsFrom(__DIR__.'/views', 'checkout');
    }

    public function boot() {
        
        include __DIR__.'/routes.php';
    }
}
