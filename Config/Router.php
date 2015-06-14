<?php 

use APIJet\Request;

return [
    'globalPattern' => [
        '{id}' => '([0-9]+)',
        '{word}' => '(\w)'
    ],
    'routes' => [
        'jobs/list/{id}/{TYPE}' => [Request::GET, 'jobs\search', ['{TYPE}' => '(BIG|SMALL|MIDDLE)']],
//         'jobs/list/{id}' => [[Request::GET, Request::PUT, Request::OPTIONS, Request::DELETE], 'jobs\search'],
        
        
    ]
];

