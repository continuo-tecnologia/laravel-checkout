<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use App\Models\Marketplace\Product;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use MatheusFS\Laravel\Checkout\Facades\Mailer;

class Postback {

    public function orders(Request $request) {

        // $user_agent = $this->validateAndGetAgent($request);
        // $normalized = Postback::normalizeOrderData($request);
        // $this->sendMails($normalized);

        // Log::info("Succesfully processed order id: $request->id (Agent: $user_agent)", $normalized);
        
        return response()->json([
            'error' => null,
            'message' => 'Postback order received correctly!',
            'order_id' => $request->id
        ]);
    }

    public function transactions(Request $request) {

        $user_agent = $this->validateAndGetAgent($request);
        $normalized = Postback::normalizeTransactionData($request);

        if(in_array($normalized['status'], array_keys(Status::MAP))){

            $this->sendMails($normalized);
        }
        
        if($normalized['status'] == 'paid'){

            $this->sendFacebookPixelEvents($normalized);
        }

        Log::info("Succesfully processed transaction id: $request->id (Agent: $user_agent)", $normalized);

        return response()->json([
            'error' => null,
            'message' => 'Postback transaction received correctly!',
            'transaction_id' => $request->id
        ]);
    }

    public function sendFacebookPixelEvents($normalized){

        $pixel_id = config('checkout.facebook.pixel_id');
        
        foreach($normalized['items'] as $item){

            $options['form_params']['data'] = [
                'event_name' => 'Purchase',
                'event_time' => Carbon::now(),
                'custom_data' => [
                    'value' => $item['unit_price'] / 100,
                    'currency' => 'BRL',
                    'transaction_id' => $normalized['id'],
                    'product_id' => $item['id'],
                    'payment_type' => $normalized['payment_method'],
                ]
            ];

            for($i = 0; $i < $item['quantity']; $i++){

                (new Client)->post(
                    "https://graph.facebook.com/v8.0/$pixel_id/events",
                    $options
                );
            }
        }
    }

    public function sendMails($normalized){
        
        $customer_email = $normalized['customer']['email'];
        $customer_mailable = Mailer::getCustomerMailable($normalized);
        Mailer::mailCustomer($customer_email, $customer_mailable);

        if($normalized['status'] == 'paid') {

            Mailer::mailSuppliers($normalized);
        }
    }

    public function validateAndGetAgent(Request $request){
        
        $user_agent = $request->header('User-Agent');
        Log::debug("Received order postback from agent: $user_agent");

        Postback::validate($request);
        return $user_agent;
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

        Log::debug("$message (Agent: $user_agent)", [$caller_method]);

        return $is_valid ? true : abort(403, $message);
    }

    public static function normalizeOrderData($request): array{
        
        $status = $request->order['status'];
        $amount = $request->order['amount'];
        $items = $request->order['items'];

        $transaction = Api::client()->transactions()->getList(['order_id' => $request->order['id']])[0];
        $payment_method = $transaction->payment_method;

        $boleto = [
            'url' => $transaction->boleto_url,
            'barcode' => $transaction->boleto_barcode,
            'expiration_date' => $transaction->boleto_expiration_date
        ];

        $payment_link = Api::client()->paymentLinks()->get(['id' => $request->order['payment_link_id']]);
        $customer = $payment_link->customer_config->customer;
        $billing = $payment_link->customer_config->billing;
        $shipping = $payment_link->customer_config->shipping;

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

    public static function normalizeTransactionData($request): array{

        $boleto = [
            'url' => $request->transaction['boleto_url'],
            'barcode' => $request->transaction['boleto_barcode'],
            'expiration_date' => $request->transaction['boleto_expiration_date']
        ];

        $order = Api::order($request->transaction['order_id']);

        return [
            'transaction_id' => $request->id,
            'status' => $request->transaction['status'] ?? 'undefined',
            'amount' => $request->transaction['amount'],
            'items' => $request->transaction['items'] ?? $order['items'],
            'boleto' => $boleto,
            'payment_method' => $request->transaction['payment_method'],
            'customer' => $request->transaction['customer'],
            'billing' => $request->transaction['billing'],
            'shipping' => $request->transaction['shipping']
        ];
    }
}
