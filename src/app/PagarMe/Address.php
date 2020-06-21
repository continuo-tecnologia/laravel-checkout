<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

use MatheusFS\LaravelCheckout\Address as LaravelCheckoutAddress;
use MatheusFS\LaravelCheckout\Traits\Requestable;

class Address {

    use Requestable;

    public $zipcode;
    public $street;
    public $street_number;
    public $complementary;
    public $neighborhood;
    public $city;
    public $state;
    public $country;

    /**
     * New Pagar.me API Address
     * 
     * @param \MatheusFS\LaravelCheckout\Address $address
     */
    public function __construct(LaravelCheckoutAddress $address) {

        $this->zipcode = $address->zipcode;
        $this->street_number = $address->street_number;
        $this->street = $address->street_name;
        $this->complementary = $address->street_complementary;
        $this->neighborhood = $address->neighborhood;
        $this->state = $address->state;
        $this->city = $address->city;
        $this->country = $address->country;
    }
}