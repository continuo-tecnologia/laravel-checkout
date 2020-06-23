<?php

namespace MatheusFS\LaravelCheckout\Contact;

use MatheusFS\LaravelCheckout\Exceptions\FormExeption;
use MatheusFS\LaravelCheckout\Traits\NumericStringable;
use MatheusFS\LaravelCheckout\Traits\FormValidable;

class Phone {

    use FormValidable, NumericStringable;

    /** 
     * @var integer 
     */
    public $ddd;

    public function __construct(string $string) {

        $this->validateNumeric($string, '/\d{10,11}/', 'Telefone inválido');
    }

    public function formated(){
        
        return preg_replace('/(\d{2})(\d{4,5})(\d{4})/', '($1)$2-$3', $this->number);
    }
}