<?php

namespace MatheusFS\Laravel\Checkout\Support\Facades;

use Illuminate\Support\Facades\Log;
use MatheusFS\Laravel\Checkout\Checkout;
use MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe\Status;

class Payment{

    public static function status($item_key){

        $last_transaction = Order::last($item_key);

        if(is_null($last_transaction)) return 'needs_payment';

        $last_status = $last_transaction->status;

        Log::debug(__CLASS__.'@'.__FUNCTION__, compact('item_key', 'last_transaction', 'last_status'));

        if($last_status === 'authorized'){

            (new Checkout)->confirm_order($last_transaction);

            return 'requested_payment';
        }

        if($last_status === 'paid') return 'paid';

        $is_processing = collect(Status::PROCESSING)->contains($last_status);
        $is_cancelled = collect(Status::CANCELLED)->contains($last_status);

        if($is_processing){

            $method = $last_transaction->payment_method;

            if($method === 'boleto') return 'requested_boleto_payment';
            if($method === 'pix') return 'requested_pix_payment';
            if($method === 'credit_card') return 'requested_cc_payment';
        }
        elseif($is_cancelled) return 'needs_payment';
    }
}