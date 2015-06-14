<?php 

namespace APIJet;

class Router
{
    private static $matchedRoutePatameters = [];
    
    private function __construct() {}
    private function __clone() {}
    
    private static function getConfig()
    {
        return Config::getByName('Router');   
    }
    
    private static function getRoutes()
    {
        return self::getConfig()['routes'];
    }
    
    private static function getGlobalPattern()
    {
        return self::getConfig()['globalPattern'];
    }
    
    /**
     * @return array of matched resource controller and action, if not matched return null
     * @param string $requestMethod
     * @param string $requestResourceUrl
     */
    public static function getMatchedRouterResource($requestMethod, $requestResourceUrl)
    {
        foreach (self::getRoutes() as $routePattern => $route) {
            
            if (self::isMatchRequestType($requestMethod, $route[0])) {
            
                if (isset($route[2])) {
                    $localUrlPattern = $route[2];
                } else {
                    $localUrlPattern = [];
                }
                
                if (self::isMatchResourceUrl($requestResourceUrl, $routePattern, $localUrlPattern)){
                    // Route matched, stop checking other router.
                    return self::parseResourceName($route[1]);
                }
            }
        }
        
        return null;
    }
    
    private static function parseResourceName($resourceName)
    {
        $strPosName = strpos($resourceName, "\\");
        return [substr($resourceName, 0, $strPosName), substr($resourceName, ++$strPosName)];
    }
    
    public static function getMachedRouteParameters()
    {
        return self::$matchedRoutePatameters;
    }
    
    private static function setMachedRouteParameters($matchedRoutePatameters)
    {
        self::$matchedRoutePatameters = $matchedRoutePatameters;
    }
    
    private static function isMatchRequestType($requestMethod, $allowedRequestMethod)
    {
        if (is_array($allowedRequestMethod)) {
            return in_array($requestMethod, $allowedRequestMethod);
        }
        
         // for short route syntax
        return ($requestMethod == $allowedRequestMethod);
    }
    
    private static function isMatchResourceUrl($requestResourceUrl, $routeResourceUrl, $localRoutePattern)
    {
        // Merge local and global pattern, local must overview global
        $routePatterns = $localRoutePattern + self::getGlobalPattern();
        
        // Applying patterns to router resource URL
        $routeResourceUrl = strtr($routeResourceUrl, $routePatterns);
        
        $machedRouteParameters = [];
        $isMatched = (bool) preg_match('#^'.$routeResourceUrl.'$#', $requestResourceUrl, $machedRouteParameters);
        
        if ($isMatched) {
            unset($machedRouteParameters[0]);
            self::setMachedRouteParameters($machedRouteParameters);
        }
        
        return $isMatched;
    }
    
}

