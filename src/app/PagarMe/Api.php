<?php

namespace MatheusFS\LaravelCheckout\PagarMe;

use PagarMe\Client;

class Api {

    const KEY = 'ak_live_uLF749Vstvw6jeNx8AH5uroEH0XAC9';
    const SANDBOX_KEY = 'ak_test_ZfkuJKLEYICsa9IB38dmMDDCc9nvHH';

    public static function client(bool $sandbox = false): Client {

        return new Client($sandbox ? Api::SANDBOX_KEY : Api::KEY);
    }
}