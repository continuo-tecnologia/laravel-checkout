<?php

namespace MatheusFS\LaravelCheckout\Entities\Documents;

use MatheusFS\LaravelCheckout\Exceptions\FormExeption;
use MatheusFS\LaravelCheckout\Traits\NumericStringable;

class CPF {

    use NumericStringable;

    public function __construct(string $string) {

        $number = preg_replace('/\D/', '', $string);
        if(preg_match('/\d{11}/', $number)){

            $this->number = $number;
        }else{

            throw new FormExeption('CPF Inválido');
        }
    }

    public function formated(){
        
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $this->number);
    }
}