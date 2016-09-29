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
