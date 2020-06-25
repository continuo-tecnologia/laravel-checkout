<?php

namespace MatheusFS\LaravelCheckout\Traits;

use MatheusFS\LaravelCheckout\Contact\Email;
use MatheusFS\LaravelCheckout\Contact\Phone;

trait Contactable {

    /** 
     * @var \MatheusFS\LaravelCheckout\Contact\Email
     */
    public $email;

    /** 
     * @var \MatheusFS\LaravelCheckout\Contact\Phone
     */
    public $phone;

    public function setEmail($email){
        
        $this->email = new Email($email);
    }

    public function setPhone($phone){
        
        $this->phone = new Phone($phone);
    }
}