<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use Carbon\Carbon;
use GuzzleHttp\Client;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

use MatheusFS\Laravel\Checkout\Checkout;
use MatheusFS\Laravel\Checkout\Events\PaymentCancelled;
use MatheusFS\Laravel\Checkout\Events\PaymentConfirmed;
use MatheusFS\Laravel\Checkout\Facades\Mailer;
use MatheusFS\Laravel\Checkout\Support\Facades\Order;

class Postback{

    public function orders(Request $request){

        // $user_agent = $this->validateAndGetAgent($request);
        // $normalized = Postback::normalizeOrderData($request);
        // Mailer::sendMails($normalized);

        // Log::info("Succesfully processed order id: $request->id (Agent: $user_agent)", $normalized);
        
        return response()->json([
            'error' => null,
            'message' => 'Postback order received correctly!',
            'order_id' => $request->id
        ]);
    }

    public function transactions(Request $request){

        $user_agent = $this->validateAndGetAgent($request);
        $normalized = Postback::normalizeTransactionData($request);

        $status = Order::status($normalized);
        $user = Order::customer($normalized);

        $mapped_status = in_array($status, array_keys(Status::MAP));

        if($mapped_status) Mailer::sendMails($normalized);

        if($status === 'paid'){

            if(app()->environment('production')){

                $this->sendFacebookPixelEvents($normalized);
            }

            PaymentConfirmed::dispatch($normalized);
        }
        elseif(collect(Status::CANCELLED)->contains($status)){

            PaymentCancelled::dispatch($normalized);
        }

        if($user) Checkout::invalidate_user_orders($user);

        Log::info("Succesfully processed transaction id: $request->id (Agent: $user_agent)", $normalized);

        return response()->json([
            'error' => null,
            'message' => 'Postback transaction received correctly!',
            'transaction_id' => $request->id
        ]);
    }

    public function sendFacebookPixelEvents($normalized){

        $pixel_id = config('checkout.facebook.pixel_id');
        $access_token = config('checkout.facebook.graph_api_access_token');
        $version = config('checkout.facebook.graph_api_version');
        $pixel_event_endpoint = "https://graph.facebook.com/$version/$pixel_id/events?access_token=$access_token";

        foreach($normalized['items'] as $item){

            $options['json'] = [
                'data' => [
                    [
                        'event_name' => 'Purchase',
                        'event_time' => Carbon::now()->timestamp,
                        'user_data' => [
                            'em' => hash('sha256', $normalized['customer']['email']),
                            'ph' => hash('sha256', ltrim($normalized['customer']['phone_numbers'][0],'+')),
                        ],
                        'custom_data' => [
                            'value' => $item['unit_price'] / 100,
                            'currency' => 'BRL',
                            'transaction_id' => $normalized['transaction_id'],
                            'product_id' => $item['id'],
                            'payment_type' => $normalized['payment_method'],
                        ]
                    ]
                ]
            ];

            for($i = 0; $i < $item['quantity']; $i++){

                (new Client)->post($pixel_event_endpoint, $options);
            }
        }
    }

    public function validateAndGetAgent(Request $request){

        $user_agent = $request->header('User-Agent');
        Log::info("Received postback from agent: $user_agent");

        Postback::validate($request);

        return $user_agent;
    }

    public static function validate(Request $request){

        $inputs = $request->all();

        $formated = collect($inputs)->map(function($item){

            if(is_array($item)){

                return collect($item)->map(function($item2){

                    if(is_array($item2)){

                        return collect($item2)->map(function($item3){

                            if(is_array($item3)){

                                $null_to_empty_string = fn($item4) => $item4 ?? '';
                                return collect($item3)->map($null_to_empty_string)->toArray();
                            }

                            return $item3 ?? '';
                        })
                        ->toArray();
                    }

                    return $item2 ?? '';
                })
                ->toArray();
            }

            return $item ?? '';
        })
        ->toArray();

        $body = http_build_query($formated, '', '&', PHP_QUERY_RFC3986);

        $signature = $request->header('X-Hub-Signature');
        $user_agent = $request->header('User-Agent');

        $fake = Session::get('fake_validation');

        if(boolval($fake)) $is_valid = true;
        else{

            $is_valid = Api::client()->postbacks()->validate($body, $signature);
        } 

        $message = $is_valid 
        ? "Validated request for " . request()->getRequestUri() . " id: $request->id" 
        : "Invalid request for " . request()->getRequestUri() . " id: $request->id";

        Log::info("$message (Agent: $user_agent)");

        if(!$is_valid) abort(403, $message);

        return null;
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

        $shipping = $request->transaction['shipping'] == ""
        ? null #$request->transaction['billing']
        : $request->transaction['shipping'];

        return [
            'transaction_id' => $request->id,
            'status' => $request->transaction['status'] ?? 'undefined',
            'amount' => $request->transaction['amount'],
            'items' => $request->transaction['items'] ?? $order['items'],
            'boleto' => $boleto,
            'payment_method' => $request->transaction['payment_method'],
            'customer' => $request->transaction['customer'],
            'billing' => $request->transaction['billing'],
            'shipping' => $shipping
        ];
    }
}
