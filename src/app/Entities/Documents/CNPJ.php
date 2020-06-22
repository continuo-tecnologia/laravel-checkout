<?php

namespace MatheusFS\LaravelCheckout\Entities\Documents;

use MatheusFS\LaravelCheckout\Exceptions\FormExeption;

class CNPJ {

    /** 
     * @var integer 
     */
    public $number;

    public function __construct(string $string) {

        $number = preg_replace('/\D/', '', $string);
        if(preg_match('/\d{14}/', $number)){

            $this->number = $number;
        }else{

            throw new FormExeption('CNPJ Inválido');
        }
    }

    public function formated(){
        
        return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $this->number);
    }
}