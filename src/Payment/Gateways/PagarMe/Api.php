<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use GuzzleHttp\Client as GuzzleHttpClient;
use Illuminate\Http\Request;
use PagarMe\Client;

class Api{

    public static function client(): Client{

        $is_production = app()->environment('production');

        $api_key = $is_production
        ? config('checkout.pagarme.api_key')
        : config('checkout.pagarme.api_sandbox_key');

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

    public function capture(Request $request){

        $request->validate(['id' => 'required|integer']);

        $id = $request->input('id');

        $transaction = static::client()->transactions()->get(compact('id'));

        $amount = optional($transaction)->amount;

        $captured_transaction = static::client()->transactions()->capture(compact('id', 'amount'));

        return response()->json($captured_transaction);
    }
}