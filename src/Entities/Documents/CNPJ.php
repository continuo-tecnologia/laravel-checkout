<?php

namespace MatheusFS\Laravel\Checkout\Entities\Documents;

use MatheusFS\Laravel\Checkout\Exceptions\FormExeption;
use MatheusFS\Laravel\Checkout\Traits\NumericStringable;
use MatheusFS\Laravel\Checkout\Traits\FormValidable;

class CNPJ {

    use FormValidable, NumericStringable;

    public function __construct(string $string) {

        $this->validateNumeric($string, '/\d{14}/');
    }

    public function formated(){
        
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $this->number);
    }
}