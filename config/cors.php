<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'https://portafolio-erikquisnia.vercel.app',
        'https://portafolio.codecima.com',  // opcional si necesitas que el backend llame a sí mismo
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],


    'max_age' => 0,

    // Token Bearer -> no necesitamos credenciales/cookies
    'supports_credentials' => false,
];
