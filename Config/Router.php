<?php 

use APIJet\Router AS R;

return [
    'globalPattern' => [
        '{id}' => '([0-9]+)',
    ],
    'routes' => [
        'hello_world' => [R::GET, 'hello\world'],        
    ]
];

