<?php

namespace MatheusFS\Laravel\Checkout\Traits;

use MatheusFS\Laravel\Checkout\Models\Purchasable as PurchasableModel;
use MatheusFS\Laravel\Checkout\Checkout;
use MatheusFS\Laravel\Checkout\Support\Facades\Order;

trait Purchasable{

    function purchasable(){

        return $this->morphOne(PurchasableModel::class, 'purchasable');
    }

    function getExternalKeyAttribute(){

        $class = get_class($this);
        $key = $this->key;

        return "$class:$key";
    }

    function getPaymentStatusAttribute(){

        return Checkout::payment_status($this->external_key);
    }

    function getBoletoUrlAttribute(){

        $last_order = Order::last($this->external_key);

        return optional($last_order)->boleto_url;
    }
}