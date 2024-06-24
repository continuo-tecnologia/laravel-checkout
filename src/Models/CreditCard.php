<?php

namespace MatheusFS\Laravel\Checkout\Models;

class CreditCard{

    protected $name;
    protected $number;
    protected $exp;
    protected $cvv;
    protected $gateway;

    public function __construct(string $name, string $number, string $exp, string $cvv){

        $this->name = $name;
        $this->number = $number;
        $this->exp = $exp;
        $this->cvv = $cvv;
        $this->gateway = 'PagarMe';
    }

    function save(){

        $class = "MatheusFS\Laravel\Checkout\Payment\Gateways\{$this->gateway}\CreditCard";

        $cc = new $class;
        $cc->save();
    }
}