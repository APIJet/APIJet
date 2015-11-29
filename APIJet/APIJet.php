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
    
    private static $defaultConfig = 
    [
        'APIJet' => [
            self::DEFAULT_RESPONSE_LIMIT => 25,
            self::AUTHORIZATION_CALLBACK => null,
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
                'hello_world' => [\APIJet\Router::GET, 'hello\world'],
            ]
        ]
   ];
    
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
            self::$rootDir = realpath(dirname(__FILE__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;
        }
        
        return self::$rootDir;
    }
    
    private $singletonContainer;
    
    public function getSingletonContainer($name)
    {
        if (isset($this->singletonContainer[$name])) {
            return $this->singletonContainer[$name];
        }
    
        return false;
    }
    
    /**
     * @return Router
     */
    public function getRouterContainer()
    {
        return $this->getSingletonContainer('Router');
    }
    
    /**
     * @return Config
     */
    public function getConfigContainer()
    {
        return $this->getSingletonContainer('Config');
    }
    
    /**
     * @return Response
     */
    public function getResponseContainer()
    {
        return $this->getSingletonContainer('Response');
    }
    
    public function __construct() 
    {
        $config = $containers['Config'] = new Config();
        $config->set(self::$defaultConfig);
        
        $routerConfig = $config->get('Router');
        
        $containers['Router'] = new Router(
            $routerConfig['routes'], 
            $routerConfig['globalPattern']
        );
        $containers['Response'] = new Response();
        
        
        $this->singletonContainer = $containers;
    }
    
    public function run()
    {
        $response = $this->getResponseContainer();
        
        if (!Request::isАuthorized()) {
            $response->setCode(401);
            return;
        }
        
        $router = $this->getRouterContainer();

        if (!$router->getMatchedRouterResource(Request::getMethod(), Request::getCleanRequestUrl())) {
            $response->setCode(404);
            return;
        }
        
        try  {
            $actionResponse = self::executeResoruceAction(
                $router->getMatchedController(),
                $router->getMatchedAction(),
                $router->getMatchedRouteParameters()
            );
            
            if ($actionResponse === false) {
                $response->setCode(404);
            } else {
                $response->setBody($actionResponse);
            }
        } catch(\Exception $e) {
            $response->setCode(500);
        }
        
        $response->render();
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

