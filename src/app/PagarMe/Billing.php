<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

use MatheusFS\LaravelCheckout\Traits\Requestable;

class Billing {

    use Requestable;
    
    public $address;
    public $name;

    public function __construct(Address $address, string $name = 'Cobrança'){

        $this->name = $name;
        $this->address = $address;
    }
}