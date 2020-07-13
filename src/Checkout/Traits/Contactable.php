<?php

namespace MatheusFS\Laravel\Checkout\Traits;

use MatheusFS\Laravel\Checkout\Entities\Contact\Email;
use MatheusFS\Laravel\Checkout\Entities\Contact\Phone;

trait Contactable {

    /** 
     * @var \MatheusFS\Laravel\Checkout\Entities\Contact\Email
     */
    public $email;

    /** 
     * @var \MatheusFS\Laravel\Checkout\Entities\Contact\Phone
     */
    public $phone;

    public function setEmail($email){
        
        $this->email = new Email($email);
    }

    public function setPhone($phone){
        
        $this->phone = new Phone($phone);
    }
}