<?php

namespace MatheusFS\LaravelCheckout\Contact;

use MatheusFS\LaravelCheckout\Exceptions\FormExeption;

class Email {

    /** 
     * @var string
     */
    public $email;

    public function __construct(string $string) {

        if(preg_match('/[A-z0-9]{3,}@[A-z0-9]{3,}.[A-z]{2,5}/', $string)){
            
            $this->email = $string;
        }else{

            throw new FormExeption('E-mail Inválido');
        }
    }

    public function formated(){
        
        return $this->__toString();
    }

    public function __toString(){
        
        return (string) $this->email;
    }
}