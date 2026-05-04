<?php

// config/jurusin.php
// Centralized app config — edit here, not in Blade or controllers

return [

    'contact' => [
        'email'     => env('JURUSIN_EMAIL',     'support@jurusin.id'),
        'instagram' => env('JURUSIN_INSTAGRAM', '@jurusin.id'),
    ],

    'social' => [
        'instagram' => env('JURUSIN_IG_URL',      'https://instagram.com/jurusin'),
        'twitter'   => env('JURUSIN_TWITTER_URL', 'https://twitter.com/jurusin'),
    ],

];
