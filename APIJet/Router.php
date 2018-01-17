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
    const PUT                 = 3;
    const DELETE              = 4;
    const HEAD                = 5;
    
    private static $matchMethodToIndex = [
        'POST' => self::POST,
        'GET' => self::GET,
        'PUT' => self::PUT,
        'DELETE' => self::DELETE,
        'HEAD' => self::HEAD
    ];
    
    private $routes; 
    private $globalPattern;
    
    private $matchedController;
    private $matchedAction;
    private $matchedPatameters = [];
    
    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }
    
    public function setGlobalPattern($globalPattern)
    {
        $this->globalPattern = $globalPattern;
    }
    
    public function getMatchedController()
    {
        return $this->matchedController;
    }
    
    public function getMatchedAction()
    {
        return $this->matchedAction;
    }
    
    public function getMatchedRouteParameters()
    {
        return $this->matchedPatameters;
    }
    
    
    /**
     * @return array of matched resource controller and action, if not matched return null
     * @param string $requestMethod
     * @param string $requestResourceUrl
     */
    public function getMatchedRouterResource($requestMethod, $requestResourceUrl)
    {
        // get only the routers for the specific type.
        $routers = $this->routes[self::$matchMethodToIndex[$requestMethod]];

        foreach ($routers as $routePattern => $route) {
        
            if (isset($route[1])) {
                $localUrlPattern = $route[1];
            } else {
                $localUrlPattern = [];
            }
            
            // Route matched, stop checking other router.
            if ($this->isMatchResourceUrl($requestResourceUrl, $routePattern, $localUrlPattern)) {
                $this->parseResourceName($route[0]);
                return true;
            }
         
        }
        return false;
    }
    
    private function parseResourceName($resourceName)
    {
        $strPosName = strrpos($resourceName, "\\");
        
        $this->matchedController = substr($resourceName, 0, $strPosName);
        $this->matchedAction = substr($resourceName, ++$strPosName);
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
            $this->matchedPatameters = $machedRouteParameters;
        }
        return $isMatched;
    }
    
}
