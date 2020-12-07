<?php

namespace MatheusFS\Laravel\Checkout\Shipping\Carriers\Correios;

use FlyingLuscas\Correios\Client;
use FlyingLuscas\Correios\Service;

class Api {

    protected $api;

    public function __construct() {
        $this->api = new Client;
    }

    public function getFreight($from, $to): array{

        $from = preg_replace('/(\d{5})/', '$1-', preg_replace('/\D/', '', $from));
        $to = preg_replace('/(\d{5})/', '$1-', preg_replace('/\D/', '', $to));

        return $this->api->freight()
            ->origin($from)
            ->destination($to)
            ->services(Service::SEDEX, Service::PAC)
            ->item(16, 16, 16, .3, 1) // largura, altura, comprimento, peso e quantidade
            ->calculate();
    }

    public function getZipcode($zipcode) {
        
        return $this->api->zipcode()->find($zipcode);
    }
}