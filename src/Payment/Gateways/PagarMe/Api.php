<?php

namespace MatheusFS\Laravel\Checkout\Payment\Gateways\PagarMe;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Http\Request;
use PagarMe\Client;

class Api{

    public static function client(): Client{

        $api_key = config('checkout.pagarme.api_key');

        return new Client($api_key);
    }

    public static function http_client(): HttpClient{

        $api_key = config('checkout.pagarme.api_key');

        $client = new HttpClient([
            'base_uri' => 'https://api.pagar.me/1/',
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode("$api_key:x"),
            ]
        ]);

        return $client;
    }

    static function get($endpoint){

        $request = Api::http_client()->get($endpoint);

        $response = $request->getBody();

        $content = $response->getContents();

        return json_decode($content, true)[0];
    }

    static function post($endpoint, $payload){

        $request = Api::http_client()->post($endpoint, ['json' => $payload]);

        $response = $request->getBody();

        $content = $response->getContents();

        return json_decode($content, true)[0];
    }

    public static function order($id){

        try{

            return Api::get("orders?id=$id");
        }
        catch(\Exception $exception){}
    }

    public function capture(Request $request){

        $request->validate([
            'id' => 'required',
            'amount' => 'integer',
        ]);

        $id = $request->input('id');
        $amount = $request->input('amount') * 100;

        $payload = compact('id', 'amount');

        // $captured_transaction = Api::post("transactions/$id/capture", compact('amount'));
        $captured_transaction = static::client()->transactions()->capture($payload);

        return response()->json($captured_transaction);
    }
}