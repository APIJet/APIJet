<?php 

namespace APIJet;

class Config
{ 
    private static $configStore = [];
    private static $baseConfigDir = null;
    
    private function __construct() {}
    private function __clone() {}
    
    /**
     * @desc if config with corresponding file doesn't exist will return an empty array
     * @param sting $name
     * @return array 
     */
    public static function getByName($name)
    {
        if (!isset(self::$configStore[$name])) {
            $configFile = @include self::getBaseConfigDir().$name.APIJet::fileExt;
            
            if ($configFile === false) {
                $configFile = [];
            }
            self::$configStore[$name] = $configFile;
        }
        
        return self::$configStore[$name];
    }
    
    private static function getBaseConfigDir()
    {
        if (self::$baseConfigDir === null) {
            self::$baseConfigDir = APIJet::getRootDir().'Config'.DIRECTORY_SEPARATOR;
        }
        return self::$baseConfigDir;
    }
}