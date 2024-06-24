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

        return $this->get_from_last_order('boleto_url');
    }

    function getQrCodeAttribute(){

        return $this->get_from_last_order('pix_qr_code');
    }

    function getTransactionIdAttribute(){

        return $this->get_from_last_order('tid');
    }

    function get_from_last_order($attribute){

        $last_order = Order::last($this->external_key);

        return optional($last_order)->{$attribute};
    }
}