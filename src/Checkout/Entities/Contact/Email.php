<?php

namespace MatheusFS\LaravelCheckout\Contact;

use MatheusFS\LaravelCheckout\Exceptions\FormExeption;
use MatheusFS\LaravelCheckout\Traits\FormValidable;

class Email {

    use FormValidable;

    /** 
     * @var string
     */
    public $email;

    public function __construct(string $string) {

        $this->validate($string, '/[A-z0-9]{3,}@[A-z0-9]{3,}.[A-z]{2,5}/');
        $this->email = $string;
    }

    public function formated(){
        
        return $this->__toString();
    }

    public function __toString(){
        
        return (string) $this->email;
    }
}