<?php

namespace MatheusFS\LaravelCheckout\Address;

use MatheusFS\LaravelCheckout\Exceptions\FormExeption;
use MatheusFS\LaravelCheckout\Traits\NumericStringable;
use MatheusFS\LaravelCheckout\Traits\FormValidable;

class Zipcode {

    use FormValidable, NumericStringable;

    public function __construct(string $string) {

        $this->validateNumeric($string, '/\d{8}/', 'CEP Inválido');
    }

    public function formated(){
        
        return preg_replace('/(\d{2})(\d{3})(\d{3})/', '$1.$2-$3', $this->number);
    }
}