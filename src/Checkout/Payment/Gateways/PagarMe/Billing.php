<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use MatheusFS\Laravel\Checkout\Traits\Requestable;

class Billing {

    use Requestable;
    
    public $address;
    public $name;

    public function __construct(Address $address, string $name = 'Cobrança'){

        $this->name = $name;
        $this->address = $address;
    }
}