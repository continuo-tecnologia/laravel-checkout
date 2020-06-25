<?php

namespace MatheusFS\Laravel\Checkout;

use MatheusFS\Laravel\Checkout\Address\Zipcode;
use MatheusFS\Laravel\Checkout\Facades\Viacep;
use MatheusFS\Laravel\Checkout\Traits\Requestable;

class Address {

    use Requestable;

    public $zipcode;
    public $street_number;
    public $street_name;
    public $street_complementary;
    public $neighborhood;
    public $city;
    public $state;
    public $country;

    /**
     * New Address
     * 
     * @param string $zipcode .
     * @param string $street_number .
     * @param string $street_complementary .
     * @param string $street_name .
     * @param string $neighborhood .
     * @param string $city .
     * @param string $state .
     * @param string $country .
     */
    public function __construct(
        $zipcode, 
        $street_number, 
        $street_complementary = '',
        $street_name = null,
        $neighborhood = null,
        $city = null,
        $state = null,
        $country = null
    ) {
        $this->zipcode = new Zipcode($zipcode);
        $this->street_number = $street_number;
        $this->street_complementary = $street_complementary;

        $address = Viacep::fetch($this->zipcode);

        $this->street_name = $street_name ?? $address['logradouro'];
        $this->neighborhood = $neighborhood ?? $address['bairro'];
        $this->city = $city ?? $address['localidade'];
        $this->state = $state ?? $address['uf'];
        $this->country = $country ?? 'BR';
    }
}