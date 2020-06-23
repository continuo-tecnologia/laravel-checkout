<?php

namespace MatheusFS\LaravelCheckout\Entities\Documents;

use MatheusFS\LaravelCheckout\Exceptions\FormExeption;
use MatheusFS\LaravelCheckout\Traits\NumericStringable;
use MatheusFS\LaravelCheckout\Traits\FormValidable;

class CNPJ {

    use FormValidable, NumericStringable;

    public function __construct(string $string) {

        $this->validateNumeric($string, '/\d{14}/');
    }

    public function formated(){
        
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $this->number);
    }
}