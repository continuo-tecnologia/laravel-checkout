<?php

namespace MatheusFS\LaravelCheckout\Facades;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use MatheusFS\LaravelCheckout\Mail\Postback\Customer as PostbackToCustomer;
use MatheusFS\LaravelCheckout\Mail\Postback\Development as PostbackToDevelopment;
use MatheusFS\LaravelCheckout\Mail\Postback\Supplier as PostbackToSupplier;

class Mailer {

    const DEVELOPMENT = ['matheus@refresher.com.br', 'marketplace@refresher.com.br'];
    const SUPPLIER = 'matheusfs@refresher.com.br';

    public static function sendMailsToInvolved(Request $request) {

        Mail::to(Mailer::DEVELOPMENT)
        ->send(new PostbackToDevelopment($request->all()));
        Logger::log('sent', 'Sent mail to '.Mailer::DEVELOPMENT);

        Mail::to($request->transaction['customer']['email'])
        ->send(new PostbackToCustomer($request->all()));
        Logger::log('sent', 'Sent mail to '.$request->transaction['customer']['email']);

        if(in_array($request->current_status, [
            'authorized', 'paid'
        ])){
            
            Mail::to(Mailer::SUPPLIER)
            ->send(new PostbackToSupplier($request->all()));
            Logger::log('sent', 'Sent mail to '.Mailer::SUPPLIER);
        }
    }
}