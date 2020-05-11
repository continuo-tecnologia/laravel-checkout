<?php

namespace MatheusFS\LaravelCheckout\Facades;

use MatheusFS\LaravelCheckout\Mail\Postback\Customer as PostbackToCustomer;
use MatheusFS\LaravelCheckout\Mail\Postback\Development as PostbackToDevelopment;
use MatheusFS\LaravelCheckout\Mail\Postback\Supplier as PostbackToSupplier;

class Mailer {

    public static function sendMailsToInvolved(){

        Mail::to()
        ->send(new PostbackToDevelopment($request->all()));
        
        Mail::to($request->transaction['customer']['email'])
        ->send(new PostbackToCustomer($request->all()));

        Mail::to('null@refresher.com.br' /* SUPPLIER E-MAIL */)
        ->send(new PostbackToSupplier($request->all()));
    }
}