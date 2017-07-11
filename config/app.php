<?php

define('ROOT_PATH', __DIR__ . '/../');

return [
    'data' => [
        'path' => ROOT_PATH . 'data',
        'encode' => function ($data) {
            return json_encode($data);
        },
        'decode' => function ($data) {
            return (array)json_decode($data, true);
        },
        'ext' => 'json'
    ],
    'controllers' => [
        'GET' => 'controllers',
        'POST' => 'controllers',
        'CONSOLE' => 'commands'
    ],
    'actions' => [
        'GET /' => 'user.auth',
        'POST /' => 'user.login',
        'POST /logout' => 'user.logout',
        'GET /settings' => 'page.settings',
        'GET /commutator' => 'page.commutator',
        'GET /sensor' => 'page.sensor',
        'CONSOLE /sensor/set/{id}/{value}' => 'sensor.set',
        'CONSOLE /settings/set/{id}/{value}' => 'settings.set',
        'CONSOLE /commutator/set/{id}/{value}' => 'commutator.set'
    ]
];