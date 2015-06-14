<?php 

namespace APIJet;

class Config
{ 
    private static $config = [];
    
    private function __construct() {}
    private function __clone() {}
    
    public static function getByName($name)
    {
        if (!isset(self::$config[$name])) {
            self::$config[$name] = include APIJet::getRootDir().'Config'.DIRECTORY_SEPARATOR.$name.'.php';
        }
        
        return self::$config[$name];
    }
}