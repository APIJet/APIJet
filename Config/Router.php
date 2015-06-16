<?php 

use APIJet\Router AS R;

return [
    'globalPattern' => [
        '{id}' => '([0-9]+)',
    ],
    'routes' => [
        'jobs/list' => [R::POST, 'jobs\list'],
        'jobs/list/{id}' => [R::GET_PUT_DELETE, 'jobs\list'],
        
        'candidates/list' => [R::POST, 'candidates\list'],
        'candidates/review/{id}' => [R::GET, 'candidates\review'],
        'candidates/search/{id}' => [R::GET, 'candidates\search'],
    ]
];

