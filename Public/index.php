<?php

require '../APIJet/APIJet.php';

use APIJet\APIJet;
use Helper\Db;

APIJet::registerAutoload();

$app = new APIJet([
    'Db' => [
        'username' => '',
        'password' => '',
        'hostname' => '',
        'database' => '',
    ],
    'Router' => [
        'globalPattern' => [
            '{id}' => '([0-9]+)',
        ],
        'routes' => [
            'hello_world' => [\APIJet\Router::GET, 'hello\world'],
        ]
    ]
]);

$config = $app->getConfigContainer();

$app->setSingletonContainer('Db', new Db($config->get('Db')));
$app->run();
