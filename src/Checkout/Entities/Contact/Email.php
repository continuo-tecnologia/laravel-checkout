<?php

namespace MatheusFS\Laravel\Checkout\Entities\Contact;

use MatheusFS\Laravel\Checkout\Exceptions\FormExeption;
use MatheusFS\Laravel\Checkout\Traits\FormValidable;

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