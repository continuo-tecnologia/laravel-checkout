<?php

namespace MatheusFS\LaravelCheckout\Entities\Documents;

use MatheusFS\LaravelCheckout\Traits\NumericStringable;
use MatheusFS\LaravelCheckout\Traits\FormValidable;

class CPF {

    use FormValidable, NumericStringable;

    public function __construct(string $string) {

        $this->validateNumeric($string, '/\d{11}/');
    }

    public function formated(){
        
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->number);
    }
}