<?php

namespace MatheusFS\LaravelCheckout\Payment\Gateways\PagarMe;

use DateTime;
use MatheusFS\LaravelCheckout\Traits\Requestable;

class Shipping {

    use Requestable;

    public $name;
    public $address;
    public $fee;
    public $delivery_date;
    public $expedited;

    public function __construct(Address $address, $fee, DateTime $delivery_date, ?string $name = 'Entrega', ?bool $expedited = false){

        $this->name = $name;
        $this->address = $address;
        $this->fee = floatval($fee) * 100;
        $this->delivery_date = $delivery_date->format('Y-m-d');
        $this->expedited = $expedited;
    }
}