<?php

namespace MatheusFS\PagarMe;

use Illuminate\Support\ServiceProvider;

class PagarMeServiceProvider extends ServiceProvider {

    public function register() {

        $this->app->make('MatheusFS\PagarMe\Facade');
        $this->loadViewsFrom(__DIR__.'/views', 'pagarme');
    }

    public function boot() {
        
        include __DIR__.'/routes.php';
    }
}
