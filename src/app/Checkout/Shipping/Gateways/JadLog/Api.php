<?php

namespace MatheusFS\LaravelCheckout\Shipping\Gateways\Jadlog;

use GuzzleHttp\Client;

class Api {

    const TOKEN = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJqdGkiOjgzNDY4LCJkdCI6IjIwMjAwNDI0In0.fi7FiLTSO1q6YNDod8GKrEBZUjLb8ODOYnbxy_CsebE';
    const BASE_URI = 'http://www.jadlog.com.br/embarcador/api/';
    const ENDPOINTS = [
        'INCLUDE' => 'pedido/incluir',
        'CANCEL' => 'pedido/cancelar',
        'TRACK' => 'tracking/consultar',
        'FREIGHT' => 'frete/valor',
        'DACTE' => 'cte/xml'
    ];

    public static function client(){
        
        $client = new Client(['base_uri' => Api::BASE_URI]);

        // $client->setDefaultOption('headers/Content-Type', 'application/json');
        // $client->setDefaultOption('headers/Authorization', 'Bearer ' . Api::TOKEN);

        return $client;
    }

    /**
     * Simulate freight
     * 
     * @param array $payload HTTP Request Payload
     * 
     * @return array Response freight
     */
    public static function freight($payload){

        $request = Api::client()->post(Api::ENDPOINTS['FREIGHT'], [
            'headers' => ['Authorization' => 'Bearer ' . Api::TOKEN],
            'json' => $payload
        ]);

        $response = $request->getBody();
        $content = $response->getContents();

        return json_decode($content, true)['frete'][0];
    }
}