<?php

return [
    'default/index' => [
        '',
        'index.php',
    ],

    'eyeglasses/list' => [
        '<attrs+:[^\-]+>-<gender:men|women>-<tags*:[^\-]+~>eyeglasses-<*:width|height|length><~page?:\d+>.html',
        'eyeglasses.html',
    ],

    'eyeglasses/gender' => [
        '<gender:men|women>-eyeglasses-page-<page:\d+>.html',
    ],

    '*/*' => '<_controller:[^/]+>/<_action:[^/]+>',
];
