<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use App\Models\Marketplace\Product;
use App\Models\User\Supplier;
use Carbon\Carbon;
use DateTime;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use MatheusFS\Laravel\Checkout\Facades\Mailer;
use MatheusFS\Laravel\Checkout\Mail\Postback\Customer as PostbackToCustomer;
use MatheusFS\Laravel\Checkout\Mail\Postback\Development as PostbackToDevelopment;
use MatheusFS\Laravel\Checkout\Mail\Postback\Supplier as PostbackToSupplier;

class Postback {

    public function orders(Request $request) {

        $user_agent = $this->validateAndGetAgent($request);
        $normalized = Postback::normalizeOrderData($request);
        $this->sendMails($normalized);

        Log::info("Succesfully processed order id: $request->id (Agent: $user_agent)", $normalized);
        
        return response()->json([
            'error' => null,
            'message' => 'Postback order received correctly!',
            'order_id' => $request->id
        ]);
    }

    public function transactions(Request $request) {

        $user_agent = $this->validateAndGetAgent($request);
        $normalized = Postback::normalizeTransactionData($request);
        $this->sendMails($normalized);
        
        if($is_paid){ $this->sendFacebookPixelEvents($normalized); }

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
        
        $customer = $normalized['customer'];
        $shipping = $normalized['shipping'];
        $items = $normalized['items'];
        $status = $normalized['status'];
        $payment_method = $normalized['payment_method'];
        $boleto = $normalized['boleto'];

        $status = [
            'subject' => Status::subject($status),
            'alias' => Status::as($status),
            'instruction' => Status::instruction($status)
        ];

        $customer_mailable = new PostbackToCustomer($customer, $shipping, $items, $status, $payment_method, $boleto);
        Mailer::mailCustomer($customer['email'], $customer_mailable);

        if($status == 'paid') {

            foreach($items as $item){
    
                $supplier_id = Product::find($item['id'])->supplier->getKey();
                $suppliers[$supplier_id]['items'][] = $item;
            }
            
            foreach($suppliers as $supplier_id => $items){
    
                $supplier = config('checkout.supplier.model')::find($supplier_id);
                $supplier_email = $supplier->{config('checkout.supplier.property_mapping.email')};
                $supplier_name = $supplier->{config('checkout.supplier.property_mapping.name')};
                $supplier_logo = $supplier->{config('checkout.supplier.property_mapping.logo')};
        
                $supplier_mailable = new PostbackToSupplier(
                    $supplier_name,
                    $supplier_logo,
                    $shipping,
                    $items,
                    $status,
                    $payment_method
                );
    
                Mailer::mailSupplier($supplier_email, $supplier_mailable);
            }
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
