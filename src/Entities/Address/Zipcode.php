<?php

namespace MatheusFS\Laravel\Checkout\Entities\Address;

use MatheusFS\Laravel\Checkout\Exceptions\FormExeption;
use MatheusFS\Laravel\Checkout\Traits\NumericStringable;
use MatheusFS\Laravel\Checkout\Traits\FormValidable;

class Zipcode {

    use FormValidable, NumericStringable;

    public function __construct(string $string) {

        $this->validateNumeric($string, '/\d{8}/', 'CEP Inválido');
    }

    public function formated(){
        
        return preg_replace('/(\d{2})(\d{3})(\d{3})/', '$1.$2-$3', $this->number);
    }
}