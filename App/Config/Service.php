<?php

return [
    'services' => [
        'name' => 'dymyw',
        'profile' => [
            'age' => 28,
            'lang' => 'php',
        ],
    ],

    'invokables' => [
        // callback function
        'db' => ['App\ServiceLocator\Invokable', 'getDbInstance'],

        // invokable class
        'front' => 'App\Controller\Front',
    ],

    'aliases' => [
        'info' => 'profile'
    ],

    'parameters' => [
    ],

    'readonly' => [
        'profile' => true
    ],
];
