<?php

namespace MatheusFS\Laravel\Checkout\Traits;

use MatheusFS\Laravel\Checkout\Contact\Email;
use MatheusFS\Laravel\Checkout\Contact\Phone;

trait Contactable {

    /** 
     * @var \MatheusFS\Laravel\Checkout\Contact\Email
     */
    public $email;

    /** 
     * @var \MatheusFS\Laravel\Checkout\Contact\Phone
     */
    public $phone;

    public function setEmail($email){
        
        $this->email = new Email($email);
    }

    public function setPhone($phone){
        
        $this->phone = new Phone($phone);
    }
}