<?php

namespace MatheusFS\LaravelCheckout\Address;

use MatheusFS\LaravelCheckout\Exceptions\FormExeption;
use MatheusFS\LaravelCheckout\Traits\NumericStringable;

class Zipcode {

    use NumericStringable;

    public function __construct(string $string) {

        $number = preg_replace('/\D/', '', $string);
        if(preg_match('/\d{8}/', $number)){

            $this->number = $number;
        }else{

            throw new FormExeption('CEP Inválido');
        }
    }

    public function formated(){
        
        return preg_replace('/(\d{2})(\d{3})(\d{3})/', '$1.$2-$3', $this->number);
    }
}