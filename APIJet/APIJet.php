<?php

namespace APIJet;

class APIJet 
{
    private static $rootDir = null;
    
    private function __construct() {}
    private function __clone() {}
    
    public static function registerAutoload()
    {
        spl_autoload_register(__NAMESPACE__ . "\\APIJet::autoload");
    }
    
    /**
     * @desc PSR-0
     * @link http://www.php-fig.org/psr/psr-0/
     */
    public static function autoload($className)
    {
        $className = ltrim($className, '\\');
        $fileName  = '';
        $namespace = '';
        
        if ($lastNsPos = strrpos($className, '\\')) {
            $namespace = substr($className, 0, $lastNsPos);
            $className = substr($className, $lastNsPos + 1);
            $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
        }
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        
        // Original PSR-0
        // require $fileName;
        
        // Adapted  
        require self::getRootDir().$fileName;
    }
    
    public static function getRootDir()
    {
        if (self::$rootDir === null) {
            self::$rootDir = realpath(dirname(__FILE__).'/../').DIRECTORY_SEPARATOR;
        }
        
        return self::$rootDir;
    }
    
    public static function runApp()
    {
        $matchedResource = Router::getMatchedRouterResource(Request::getMethod(), Request::getCleanRequestUrl());
        
        if ($matchedResource === null) {
            echo "404"; exit;
        }
        
        $response = self::executeResoruceAction($matchedResource[0], $matchedResource[1], Router::getMachedRouteParameters());
        
    }
    
    private static function executeResoruceAction($controller, $action, $patameters)
    {
        $controller = 'Controller\\'.ucfirst($controller);
        $action = strtolower(Request::getMethod()).'_'.$action;
        
        // @todo catch the error and return not exist resource not exit.
        return (array) call_user_func_array(array(new $controller(), $action), $patameters);
    }
}

