<?php

namespace MatheusFS\LaravelCheckoutPagarMe;

use PagarMe\Client;

class Api{

    const KEY = 'ak_live_uLF749Vstvw6jeNx8AH5uroEH0XAC9';

    public static function Client():Client{

        return new Client(Api::KEY);
    } 
}