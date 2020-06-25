<?php

namespace MatheusFS\LaravelCheckout\Traits;

trait NumericStringable {

    /** 
     * @var integer 
     */
    public $number;

    /**
     * String value
     * 
     * @return string
     */
    public function __toString(){
        
        return (string) $this->number;
    }
}