<?php

// Konfigurasi integrasi terpusat. Ganti provider cukup lewat .env.
return [
    'whatsapp' => [
        'provider' => env('WHATSAPP_PROVIDER', 'fonnte'), // fonnte | onesender | starsender
        'fonnte' => [
            'token' => env('FONNTE_TOKEN'),
            'device' => env('FONNTE_DEVICE'),
        ],
        'onesender' => [
            'token' => env('ONESENDER_TOKEN'),
            'url' => env('ONESENDER_URL'),
        ],
        'starsender' => [
            'token' => env('STARSENDER_TOKEN'),
        ],
    ],

    'email' => [
        'provider' => env('EMAIL_PROVIDER', 'mailketing'), // mailketing | kirim_email
        'mailketing' => [
            'token' => env('MAILKETING_API_TOKEN'),
        ],
        'kirim_email' => [
            'token' => env('KIRIMEMAIL_API_TOKEN'),
        ],
        'from_email' => env('MAIL_FROM_ADDRESS', 'no-reply@example.com'),
        'from_name' => env('MAIL_FROM_NAME', 'KKMB Connect'),
    ],

    'payment' => [
        'provider' => env('PAYMENT_PROVIDER', 'manual_transfer'), // manual_transfer | qris_manual | midtrans(fase 2)
        'bank_name' => env('BANK_NAME', 'Bank BCA'),
        'bank_account_no' => env('BANK_ACCOUNT_NO', ''),
        'bank_account_name' => env('BANK_ACCOUNT_NAME', 'Koperasi KKMB'),
        'qris_image_path' => env('QRIS_IMAGE_PATH', 'images/qris.png'),
        'midtrans' => [
            'server_key' => env('MIDTRANS_SERVER_KEY'),
            'client_key' => env('MIDTRANS_CLIENT_KEY'),
            'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
        ],
    ],

    'builder' => [
        'name' => env('BUILDER_NAME', 'Java Maya Studio'),
        'wa' => env('BUILDER_WA', 'https://wa.me/6285722224391'),
    ],
];
