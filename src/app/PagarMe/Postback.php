<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MatheusFS\LaravelCheckout\Facades\Logger;
use MatheusFS\LaravelCheckout\Facades\Mailer;

class Postback {

    public function orders(Request $request) {

        $user_agent = $request->header('User-Agent');
        Logger::log('received', "From agent: $user_agent", __FUNCTION__);

        Postback::validate($request);
        
        $normalized = Postback::normalizeOrderData($request);
        Mailer::sendMailsToInvolved($normalized);

        Logger::log(
            'success', 
            "Succesfully processed order id: $request->id (Agent: $user_agent)", 
            __FUNCTION__
        );
        return response()->json([
            'error' => null,
            'message' => 'Postback order received correctly!',
            'order_id' => $request->id
        ]);
    }

    public function transactions(Request $request) {

        $user_agent = $request->header('User-Agent');
        Logger::log('received', "From agent: $user_agent", __FUNCTION__);

        Postback::validate($request);

        $normalized = Postback::normalizeTransactionData($request);
        Mailer::sendMailsToInvolved($normalized);

        Logger::log(
            'success', 
            "Succesfully processed transaction id: $request->id (Agent: $user_agent)", 
            __FUNCTION__
        );
        return response()->json([
            'error' => null,
            'message' => 'Postback transaction received correctly!',
            'transaction_id' => $request->id
        ]);
    }

    public static function validate($request) {

        $caller_method = debug_backtrace()[1]['function'];

        $body = $request->getContent();
        $signature = $request->header('X-Hub-Signature');
        $user_agent = $request->header('User-Agent');

        $is_valid = Api::client()->postbacks()->validate($body, $signature);
        
        $type = $is_valid ? 'valid' : 'invalid';

        $message = $is_valid 
        ? "Validated request for $caller_method id: $request->id" 
        : "Invalid request for $caller_method id: $request->id";

        Logger::log($type, "$message (Agent: $user_agent)", $caller_method);

        return $is_valid ? true : abort(403, $message);
    }

    public static function normalizeOrderData($request){
        
        $status = $request->order['status'];
        $amount = $request->order['amount'];
        $items = $request->order['items'];

        $transaction = Api::client()->transactions()->getList(['order_id' => $request->order['id']])[0];
        $payment_method = $transaction['payment_method'];
        $boleto = [
            'url' => $transaction['boleto_url'],
            'barcode' => $transaction['boleto_barcode'],
            'expiration_date' => $transaction['boleto_expiration_date']
        ];

        $payment_link = Api::client()->paymentLinks()->get(['id' => $request->order['payment_link_id']]);
        $customer = $payment_link['customer_config']['customer'];
        $billing = $payment_link['customer_config']['billing'];
        $shipping = $payment_link['customer_config']['shipping'];

        return [
            'status' => $status ?? 'undefined',
            'amount' => $amount,
            'items' => $items,
            'boleto' => $boleto,
            'payment_method' => $payment_method,
            'customer' => $customer,
            'billing' => $billing,
            'shipping' => $shipping
        ];
    }

    public static function normalizeTransactionData($request){

        $payment_method = $request->transaction['payment_method'];
        $boleto = [
            'url' => $request->transaction['boleto_url'],
            'barcode' => $request->transaction['boleto_barcode'],
            'expiration_date' => $request->transaction['boleto_expiration_date']
        ];
        $status = $request->transaction['status'];
        $amount = $request->transaction['amount'];
        $customer = $request->transaction['customer'];
        $billing = $request->transaction['billing'];
        $shipping = $request->transaction['shipping'];

        $order = Api::order($request->transaction['order_id']);
        $items = $order->items;

        return [
            'status' => $status ?? 'undefined',
            'amount' => $amount,
            'items' => $items,
            'boleto' => $boleto,
            'payment_method' => $payment_method,
            'customer' => $customer,
            'billing' => $billing,
            'shipping' => $shipping
        ];
    }
}