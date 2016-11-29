<?php

return [
    'services' => [
    ],

    'invokables' => [
        // callback function
        'db'        => ['App\ServiceLocator\Invokable', 'getDbInstance'],
        'params'    => ['App\ServiceLocator\Invokable', 'getParams'],
        'redis'     => ['App\ServiceLocator\Invokable', 'getRedisInstance'],

        // invokable class
        'frontController'   => 'Core\Controller\FrontController',
    ],

    'aliases' => [
    ],

    'parameters' => [
    ],

    'readonly' => [
    ],
];
