<?php

namespace MatheusFS\Laravel\Checkout\Contact;

use MatheusFS\Laravel\Checkout\Exceptions\FormExeption;
use MatheusFS\Laravel\Checkout\Traits\NumericStringable;
use MatheusFS\Laravel\Checkout\Traits\FormValidable;

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