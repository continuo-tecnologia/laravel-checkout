<?php

namespace MatheusFS\Laravel\Checkout;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider {

    public function register() {

        $this->mergeConfigFrom(__DIR__.'/../../config/checkout.php', 'checkout');
    }
    
    public function boot() {

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../../config/checkout.php' => config_path('checkout.php'),
            ], 'config');
        
        }
        $this->loadViewsFrom(__DIR__.'/views', 'checkout');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
