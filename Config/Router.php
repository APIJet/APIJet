<?php 

use APIJet\Request;

return [
    'globalPattern' => [
        '{id}' => '([0-9]+)',
    ],
    'routes' => [
        'hello_world' => [Request::GET, 'hello\world'],        
    ]
];

