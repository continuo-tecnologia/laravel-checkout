<?php

namespace MatheusFS\LaravelCheckout\Address;

class Zipcode {

    /** @var integer $number Zipcode number */
    public $number;

    public function __construct(string $string) {

        $this->number = preg_replace('/\D/', '', $string);
    }

    public function formated(){
        
        return preg_replace('/(\d{2})(\d{3})(\d{3})/', '$1.$2-$3', $this->number);
    }

}