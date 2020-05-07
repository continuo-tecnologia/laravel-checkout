<?php

namespace MatheusFS\PagarMe;

class Address {

    protected $street;
    protected $street_number;
    protected $zipcode;
    protected $country;
    protected $state;
    protected $city;
    protected $neighborhood;
    // protected $complementary;

    public function __construct(string $street, string $street_number, string $zipcode, string $country, string $state, string $city, string $neighborhood, $complementary = null) {
        $this->street = $street;
        $this->street_number = $street_number;
        $this->zipcode = $zipcode;
        $this->country = $country;
        $this->state = $state;
        $this->city = $city;
        $this->neighborhood = $neighborhood;
        if(isset($complementary)) $this->complementary = $complementary;
    }

    public function toArray(){return get_object_vars($this);}
}