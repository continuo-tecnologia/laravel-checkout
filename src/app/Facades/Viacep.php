<?php

namespace MatheusFS\LaravelCheckout\Facades;

use GuzzleHttp\Client;

class Viacep {

    public static function client(){
        
        return new Client(['base_uri' => 'https://viacep.com.br/']);
    }

    /**
     * Fetch address information from Viacep API
     */
    public static function fetch($zipcode){
        
        $content = Viacep::client()
        ->get("ws/$zipcode/json")
        ->getBody()
        ->getContents();

        return json_decode($content, true);
    }
}