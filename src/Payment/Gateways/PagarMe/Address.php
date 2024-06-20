<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

class Address {

    public $zipcode;
    public $street;
    public $street_number;
    // public $complementary;
    public $neighborhood;
    public $city;
    public $state;
    public $country;

    /**
     * New Pagar.me API Address adapter
     * 
     * @param \MatheusFS\Laravel\Checkout\Address $address
     */
    public function __construct($address) {
        
        $this->zipcode = "$address->zipcode";
        $this->street_number = $address->street_number;
        $this->street = $address->street_name;
        if(!empty($address->street_complementary)) $this->complementary = $address->street_complementary;
        $this->neighborhood = $address->neighborhood;
        $this->state = $address->state;
        $this->city = $address->city;
        $this->country = $address->country;
    }
}