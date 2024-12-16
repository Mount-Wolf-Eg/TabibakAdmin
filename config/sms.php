<?php

return [
    'default'   => env('SMS_PROVIDER', 'connectsaudi'),
    'providers' => [
        'taqnyat' => [
            'bearer' => env('TAQNYAT_SMS_BEARER'),
        ],
        'connectsaudi' => [
            'url'      => env('CONNECT_SAUDI_URL'),
            'user'     => env('CONNECT_SAUDI_USER'),
            'pwd'      => env('CONNECT_SAUDI_PWD'),
            'senderid' => env('CONNECT_SAUDI_SENDER_ID'),
        ]
    ]
];
