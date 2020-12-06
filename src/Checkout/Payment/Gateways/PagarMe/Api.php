<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use GuzzleHttp\Client as GuzzleHttpClient;
use PagarMe\Client;

class Api {

    public static function client(bool $sandbox = false): Client {

        $api_key = env('APP_ENV') == 'production' 
        ? config('checkout.pagarme.api_key', 'ak_live_xxxxxx')
        : config('checkout.pagarme.api_sandbox_key', 'ak_test_xxxxxx');

        return new Client($api_key);
    }

    public static function order($id){
        
        $client = new GuzzleHttpClient(['base_uri' => 'https://api.pagar.me/1/']);

        $payload = ['api_key' => config('checkout.pagarme.api_key', 'ak_live_xxxxxx')];
        $request = $client->get("orders?id=$id", ['form_params' => $payload]);

        $response = $request->getBody();

        $content = $response->getContents();
        return json_decode($content, true)[0];
    }
}