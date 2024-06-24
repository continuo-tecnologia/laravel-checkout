<?php

return [

    'name' => env('APP_NAME') . ' Shop',

    'disk' => 's3',

    'logo' => '',

    'date_timezone' => 'America/Sao_Paulo',

    'mailling' => [

        'from' => env('MAIL_FROM_ADDRESS', 'example@domain.com'),

        'copies' => [
            'example2@domain.com'
        ]
    ],

    'user' => [

        'model' => Illuminate\Foundation\Auth\User::class,
    ],

    'supplier' => [

        'model' => Illuminate\Foundation\Auth\User::class,
        'property_mapping' => [
            'logo' => 'logo',
            'name' => 'name',
            'email' => 'email'
        ]
    ],

    'pagarme' => [

        'api_key' => env('CHECKOUT_PAGARME_API_KEY', 'ak_test_xxxxxx'),
        'encryption_key' => env('CHECKOUT_PAGARME_ENCRYPTION_KEY', 'ek_test_xxxxxx'),
    ],

    'facebook' => [

        'graph_api_version' => 'v8.0',
        'graph_api_access_token' => env('FACEBOOK_GRAPH_API_ACCESS_TOKEN'),
        'pixel_id' => '562881037919202'
    ],
];