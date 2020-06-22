<?php

namespace MatheusFS\LaravelCheckout\Contact;

use MatheusFS\LaravelCheckout\Exceptions\FormExeption;
use MatheusFS\LaravelCheckout\Traits\NumericStringable;

class Phone {

    use NumericStringable;

    /** 
     * @var integer 
     */
    public $ddd;

    public function __construct(string $string) {

        $number = preg_replace('/\D/', '', $string);
        if(preg_match('/\d{10,11}/', $number)){
            
            $this->ddd = substr($number, 0, 2);
            $this->number = $number;
        }else{

            throw new FormExeption('Telefone Inválido');
        }
    }

    public function formated(){
        
        return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1)$2-$3', $this->number);
    }
}