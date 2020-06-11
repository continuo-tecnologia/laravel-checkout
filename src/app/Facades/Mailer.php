<?php

namespace MatheusFS\LaravelCheckout\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use MatheusFS\LaravelCheckout\Mail\Postback\Customer as PostbackToCustomer;
use MatheusFS\LaravelCheckout\Mail\Postback\Development as PostbackToDevelopment;
use MatheusFS\LaravelCheckout\Mail\Postback\Supplier as PostbackToSupplier;

class Mailer {

    const DEVELOPMENT = ['matheus@refresher.com.br', 'marketplace@refresher.com.br'];
    const SUPPLIER = 'falkk@studiomenin.com';

    public static function sendMailsToInvolved($data) {

        Mail::to(Mailer::DEVELOPMENT)->send(new PostbackToCustomer($data));
        Mail::to(Mailer::DEVELOPMENT)->send(new PostbackToSupplier($data));
        Logger::log('sent', 'Sent mail to ' . Mailer::DEVELOPMENT);

        Mail::to($data['customer']['email'])->send(new PostbackToCustomer($data));
        Logger::log('sent', 'Sent mail to ' . $data['customer']['email']);

        if(in_array($data['status'], [
            'authorized', 'paid'
        ])){
            
            Mail::to(Mailer::SUPPLIER)->send(new PostbackToSupplier($data));
            Logger::log('sent', 'Sent mail to ' . Mailer::SUPPLIER);
        }
    }
}