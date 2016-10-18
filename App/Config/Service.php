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
        'db'        => ['App\ServiceLocator\Invokable', 'getDbInstance'],
        'params'    => ['App\ServiceLocator\Invokable', 'getParams'],

        // invokable class
        'frontController'   => 'Core\Controller\FrontController'
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
