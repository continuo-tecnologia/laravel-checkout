<?php

namespace MatheusFS\Laravel\Checkout\Traits;

use MatheusFS\Laravel\Checkout\Exceptions\FormExeption;

trait FormValidable {

    protected $invalid_message;

    public function validateNumeric($string, $pattern, $message = null){
        
        $number = preg_replace('/\D/', '', $string);
        $this->validate($number, $pattern, $message);
        $this->number = $number;
    }

    public function validate($string, $pattern, $message = null){
        
        $this->invalid_message = $message ?? class_basename($this) . " inválido";

        if(preg_match($pattern, $string)){

            return true;
        }else{

            throw new FormExeption($this->invalid_message);
        }
    }
}