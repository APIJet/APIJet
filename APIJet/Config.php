<?php 

namespace APIJet;

use APIJet\Router AS R;

class Config
{ 
    private static $configStore = null;
    private static $defaultConfig = [
        'APIJet' => [
            APIJet::DEFAULT_RESPONSE_LIMIT => 25,
            APIJet::AUTHORIZATION_CALLBACK => null,
        ],
        'Db' => [
            'hostname' => '',
            'database' => '',
            'username' => '',
            'password' => '',
        ],
        'Router' => [
            'globalPattern' => [
                '{id}' => '([0-9]+)',
            ],
            'routes' => [
                'hello_world' => [R::GET, 'hello\world'],
            ]
        ]
    ];
    
    private function __construct() {}
    private function __clone() {}
    
    /**
     * @desc if config with corresponding file doesn't exist it will return an empty array
     * @param string $name
     * @return array 
     */
    public static function getByName($name)
    {
        self::load();
        return self::$configStore[$name];
    }
    
    private static function load()
    {
        if (self::$configStore === null) {
            self::$configStore = self::$defaultConfig;
        }
    }
    
    public static function setConfig(array $newConfig)
    {
        self::load();
        self::$configStore = $newConfig + self::$configStore;
    }
    
}