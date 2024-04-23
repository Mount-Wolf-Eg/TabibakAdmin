<?php

return [
    'default' => env('SMS_PROVIDER', 'taqnyat'),
    'providers' => [
        'taqnyat' => [
            'bearer' => env('TAQNYAT_SMS_BEARER'),
        ]
    ]
];
