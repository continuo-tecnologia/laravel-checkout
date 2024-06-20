<?php

namespace MatheusFS\Laravel\Checkout\Entities;

use MatheusFS\Laravel\Checkout\Entities\Documents\CPF;
use MatheusFS\Laravel\Checkout\Traits\Contactable;

class Person {

    use Contactable;

    /**
     * @var string
     */
    public $firstname;

    /**
     * @var string
     */
    public $lastname;

    /**
     * @var \MatheusFS\Laravel\Checkout\Entities\Documents\CPF
     */
    public $document;

    public function __construct($name, $email, $document, $phone) {

        $this->firstname = explode(' ', $name)[0];
        $this->lastname = explode(' ', $name)[1] ?? '';
        $this->setEmail($email);
        $this->document = new CPF($document);
        $this->setPhone($phone);
    }
}