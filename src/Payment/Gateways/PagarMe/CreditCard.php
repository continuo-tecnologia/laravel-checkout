<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use MatheusFS\Laravel\Checkout\Models\CreditCard as BaseCreditCard;

class CreditCard extends BaseCreditCard{

    function payload(){

        return [
            'holder_name' => $this->name,
            'number' => $this->number,
            'expiration_date' => $this->exp,
            'cvv' => $this->cvv,
        ];
    }

    function save(){

        Api::client()->cards()->create($this->payload());
    }
}