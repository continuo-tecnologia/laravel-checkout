<?php

namespace MatheusFS\Laravel\Checkout\Traits;

trait Requestable {

    /**
     * Format payload data for API request
     * 
     * @return array HTTP Request Payload
     */
    public function payload(){
        
        return get_object_vars($this);
    }
}