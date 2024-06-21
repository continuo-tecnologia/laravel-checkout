<?php

namespace MatheusFS\Laravel\Checkout;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider{

    public function register(){

        $this->mergeConfigFrom($this->get_path('/config/checkout.php'), 'checkout');
    }

    public function boot(){

        if($this->app->runningInConsole()){

            $this->publishes([
                $this->get_path('/config/checkout.php') => config_path('checkout.php'),
            ], 'config');
        }

        $this->loadViewsFrom($this->get_path('/views'), 'checkout');
        $this->loadMigrationsFrom($this->get_path('/database/migrations'));
        $this->loadRoutesFrom($this->get_path('/routes/api.php'));
        $this->loadRoutesFrom($this->get_path('/routes/console.php'));
    }

    function get_path($path = ''){

        return dirname(__DIR__) . $path;
    }
}
