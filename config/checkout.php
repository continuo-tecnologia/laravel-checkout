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

    'supplier' => [

        'model' => App\Models\User\Supplier::class,
        'property_mapping' => [
            'logo' => 'logo',
            'name' => 'name',
            'email' => 'email'
        ]
    ],

    'pagarme' => [

        'api_key' => 'ak_live_xyz',
        'api_sandbox_key' => 'ak_test_xyz',
    ],

    'facebook' => [

        'graph_api_version' => 'v8.0',
        'graph_api_access_token' => '',
        'pixel_id' => '0000'
    ]
];