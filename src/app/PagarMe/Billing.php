<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

class Billing {
    
    protected $address;
    protected $name;

    public function __construct(Address $address, string $name = 'Cobrança'){

        $this->name = $name;
        $this->address = $address->toArray();
    }

    public function toArray(){return get_object_vars($this);}
}