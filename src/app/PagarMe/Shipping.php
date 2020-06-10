<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

use DateTime;

class Shipping {

    protected $name;
    protected $address;
    public $fee;
    protected $delivery_date;
    protected $expedited;


    public function __construct(Address $address, $fee, DateTime $delivery_date, ?string $name = 'Entrega', ?bool $expedited = false){

        $this->name = $name;
        $this->address = $address->toArray();
        $this->fee = floatval($fee) * 100;
        $this->delivery_date = $delivery_date->format('Y-m-d');
        $this->expedited = $expedited;
    }

    public function toArray(){return get_object_vars($this);}
}