<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

use GuzzleHttp\Client as GuzzleHttpClient;
use PagarMe\Client;

class Api {

    const KEY = 'ak_live_uLF749Vstvw6jeNx8AH5uroEH0XAC9';
    const SANDBOX_KEY = 'ak_test_ZfkuJKLEYICsa9IB38dmMDDCc9nvHH';

    public static function client(bool $sandbox = false): Client {

        return new Client($sandbox ? Api::SANDBOX_KEY : Api::KEY);
    }

    public static function order($id){
        
        $client = new GuzzleHttpClient(['base_uri' => 'https://api.pagar.me/1/']);
        $payload = ['api_key' => 'ak_live_uLF749Vstvw6jeNx8AH5uroEH0XAC9'];
        $request = $client->get("orders?id=$id", ['form_params' => $payload]);
        $response = $request->getBody();
        $content = $response->getContents();

        return json_decode($content)[0];
    }
}