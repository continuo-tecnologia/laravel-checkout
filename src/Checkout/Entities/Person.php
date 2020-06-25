<?php

namespace MatheusFS\LaravelCheckout\Entities;

use MatheusFS\LaravelCheckout\Entities\Documents\CPF;
use MatheusFS\LaravelCheckout\Traits\Contactable;

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
     * @var \MatheusFS\LaravelCheckout\Entities\Documents\CPF
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