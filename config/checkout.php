<?php

use Illuminate\Support\Facades\Storage;

return [

    'name' => 'REFRESHER Shop',

    'disk' => 's3',

    'logo' => Storage::disk('s3')->url('loja/logo1.png'),

    'date_timezone' => 'America/Sao_Paulo',

    'mailling' => [

        'from' => env('MAIL_FROM_ADDRESS', 'contato@refreshertrends.com.br'),

        'copies' => [
            'matheus@refresher.com.br',
            'marketplace@refresher.com.br'
        ]
    ],

    'supplier' => [

        'model' => App\Models\User\Supplier::class,
        'property_mapping' => [
            'logo' => 'logo',
            'name' => 'nome_empresa',
            'email' => 'email'
        ]
    ],

    'pagarme' => [

        'api_key' => 'ak_live_uLF749Vstvw6jeNx8AH5uroEH0XAC9',
        'api_sandbox_key' => 'ak_test_ZfkuJKLEYICsa9IB38dmMDDCc9nvHH',
    ],

    'facebook' => [

        'pixel_id' => '562881037919202'
    ]
];