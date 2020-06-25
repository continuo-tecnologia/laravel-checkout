<?php

namespace MatheusFS\Laravel\Checkout\Entities\Documents;

use MatheusFS\Laravel\Checkout\Traits\NumericStringable;
use MatheusFS\Laravel\Checkout\Traits\FormValidable;

class CPF {

    use FormValidable, NumericStringable;

    public function __construct(string $string) {

        $this->validateNumeric($string, '/\d{11}/');
    }

    public function formated(){
        
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->number);
    }
}