<?php 

namespace APIJet;

class Router
{
    // First bit is for POST.
    // Second bit is for GET.
    // Third bit is for PUT.
    // Forth bit is for DELETE
    
    const POST                = 1;
    const GET                 = 2;
    const POST_GET            = 3;
    const PUT                 = 4;
    const POST_PUT            = 5;
    const GET_PUT             = 6;
    const POST_GET_PUT        = 7;
    const DELETE              = 8;
    const POST_DELETE         = 9;
    const GET_DELETE          = 10;
    const POST_GET_DELETE     = 11;
    const PUT_DELETE          = 12;
    const POST_PUT_DELETE     = 13;
    const GET_PUT_DELETE      = 14;
    const POST_GET_PUT_DELETE = 15;
    const ALL = self::POST_GET_PUT_DELETE;
    
    private static $matchMethodToIndex = [
        'POST' => self::POST,
        'GET' => self::GET,
        'PUT' => self::PUT,
        'DELETE' => self::DELETE
    ];
    
    private static $matchedRoutePatameters = [];
    
    private $routes; 
    private $globalPattern; 
    
    public function __construct(array $routes, array $globalPattern) 
    {
        $this->routes = $routes;
        $this->globalPattern = $globalPattern;
    }
    
    /**
     * @return array of matched resource controller and action, if not matched return null
     * @param string $requestMethod
     * @param string $requestResourceUrl
     */
    public function getMatchedRouterResource($requestMethod, $requestResourceUrl)
    {
        foreach ($this->routes as $routePattern => $route) {
            
            if (self::isMatchRequestType($requestMethod, $route[0])) {
            
                if (isset($route[2])) {
                    $localUrlPattern = $route[2];
                } else {
                    $localUrlPattern = [];
                }
                
                if ($this->isMatchResourceUrl($requestResourceUrl, $routePattern, $localUrlPattern)){
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
    
    public function getMachedRouteParameters()
    {
        return self::$matchedRoutePatameters;
    }
    
    private function setMachedRouteParameters($matchedRoutePatameters)
    {
        self::$matchedRoutePatameters = $matchedRoutePatameters;
    }
    
    private static function isMatchRequestType($requestMethod, $allowedRequestMethod)
    {
        $requestMethodBitwiseValue = self::$matchMethodToIndex[$requestMethod];
        
        return (($requestMethodBitwiseValue & $allowedRequestMethod) == $requestMethodBitwiseValue);
    }
    
    private function isMatchResourceUrl($requestResourceUrl, $routeResourceUrl, array $localRoutePattern)
    {
        // Merge local and global pattern, local must overview global
        $routePatterns = $localRoutePattern + $this->globalPattern;
        
        // Applying patterns to router resource URL
        $routeResourceUrl = strtr($routeResourceUrl, $routePatterns);
        
        $machedRouteParameters = [];
        $isMatched = (bool) preg_match('#^'.$routeResourceUrl.'$#', $requestResourceUrl, $machedRouteParameters);
        
        if ($isMatched) {
            unset($machedRouteParameters[0]);
            $this->setMachedRouteParameters($machedRouteParameters);
        }
        
        return $isMatched;
    }
    
}

