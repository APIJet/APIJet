<?php

namespace APIJet;

class APIJet 
{
    const fileExt = '.php';
    private static $rootDir = null;
    private static $apiJetConfig = null;
    
    // List of configurable name settings.
    const DEFAULT_RESPONSE_LIMIT = 0;
    const AUTHORIZATION_CALLBACK = 1;
    
    // Default configure value which can be overwrite by APIJet config file.
    private static $apiJetDefaultConfig = [
        self::DEFAULT_RESPONSE_LIMIT => 25,
        self::AUTHORIZATION_CALLBACK => null // null means not auth
    ];
    
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
        $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . self::fileExt;
        
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
    
    public static function getAPIJetConfig($propertyName)
    {
        if (self::$apiJetConfig === null) {
            self::$apiJetConfig = Config::getByName('APIJet') + self::$apiJetDefaultConfig;
        }
    
        return self::$apiJetConfig[$propertyName];
    }
    
    public static function runApp()
    {
        if (!Request::isАuthorized()) {
            Response::setCode(401);
            return;
        }
        
        $matchedResource = Router::getMatchedRouterResource(Request::getMethod(), Request::getCleanRequestUrl());

        if ($matchedResource === null) {
            Response::setCode(404);
            return;
        }
        
        try  {
            $response = self::executeResoruceAction(
                $matchedResource[0], 
                $matchedResource[1], 
                Router::getMachedRouteParameters()
            );
            
            if ($response === false) {
                Response::setCode(404);
            } else {
                Response::setBody($response);
            }
        } catch(\Exception $e) {
            Response::setCode(500);
        }
    }
    
    /**
     * @return response of executed action or false in case it doesn't exist
     * @param string $controller
     * @param string $action
     * @param string  $parameters
     */
    private static function executeResoruceAction($controller, $action, $parameters)
    {
        $controller = ucfirst($controller);
        $action = strtolower(Request::getMethod()).'_'.$action;
        
        // Check if controller file exist
        if (!file_exists(self::getRootDir().'Controller/'.$controller.self::fileExt))  {
            return false;
        }
        
        $controller = 'Controller\\'.$controller;
        
        // Check if class exist
        if (!class_exists($controller)) {
            return false;
        }
        
        $controllerInstance = new $controller();
        
        // Check if action exist
        if (!method_exists($controllerInstance, $action)) {
            return false;
        }
        
        // Check if it's callable
        if (!is_callable([$controllerInstance, $action])) {
            return false;
        }
        
        return (array) call_user_func_array(array($controllerInstance, $action), $parameters);
    }
}

