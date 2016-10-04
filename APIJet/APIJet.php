<?php

namespace APIJet;

class APIJet 
{
    const fileExt = '.php';
    
    private $singletonContainer;
    
    private $authorizationCallback = null;
    private $defaultResponseLimit = 25;
    private $authorizationExceptionResources = '';

    public function __construct(array $userConfig = [], array $containers = [])
    {
        if (!isset($containers['Config'])) {
            $containers['Config'] = new Config();
        }
        $config = $containers['Config'];
        $config->set($userConfig);
        
        $this->singletonContainer = $containers;
    }
    
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
        require '../'.$fileName;
    }
    
    public function getSingletonContainer($name)
    {
        if (isset($this->singletonContainer[$name])) {

            $instance = $this->singletonContainer[$name];

            if ($instance instanceof \Closure) {
                $this->singletonContainer[$name] = $instance();
            }
            return $this->singletonContainer[$name];
        }
        trigger_error('Singleton container with '.$name.' does not exist', E_USER_ERROR);
    }

    public function setSingletonContainer($name, $instance) 
    {
        $this->singletonContainer[$name] = $instance;
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
     * @return Request
     */
    public function getRequestContainer()
    {
        return $this->getSingletonContainer('Request');
    }
    
    /**
     * @return Response
     */
    public function getResponseContainer()
    {
        return $this->getSingletonContainer('Response');
    }
    
    /**
     * @desc Initialize basic container excluding config
     */
    private function initBaseContainers($containers)
    {
        $config = $this->getConfigContainer();
        $routerConfig = $config->get('Router');
        
        if (!isset($containers['Router'])) {
            $containers['Router'] = new Router();
        }
        $routerContainer = $containers['Router'];
        $routerContainer->setRoutes($routerConfig['routes']);
        $routerContainer->setGlobalPattern($routerConfig['globalPattern']);
        
        if (!isset($containers['Request'])) {
            $containers['Request'] = new Request();
        }
        $requestContainer = $containers['Request'];
        $requestContainer->setAuthorizationCallback($this->authorizationCallback);
        $requestContainer->setDefaultResponseLimit($this->defaultResponseLimit);
        
        if (!isset($containers['Response'])) {
            $containers['Response'] = new Response();
        }
        $this->singletonContainer = $containers + $this->singletonContainer;
    }

    public function setAuthorizationCallback($authorizationCallback, $exceptionResources)
    {
        $this->authorizationCallback = $authorizationCallback;
        $this->authorizationExceptionResources = $exceptionResources;
    }

    public function setDefaultResponseLimit($defaultResponseLimit)
    {
        $this->defaultResponseLimit = $defaultResponseLimit;
    }
    
    public function run($containers = [])
    {
    	$this->initBaseContainers($containers);

        $request = $this->getRequestContainer();
        $response = $this->getResponseContainer();

        $requestUrl = $request::getCleanRequestUrl();
        
        // don't check authorization if the current resource url is in exception. 
        if (! (bool) preg_match('#^'.$this->authorizationExceptionResources.'$#', $requestUrl)) {
            if (!$request->isАuthorized()) {
                $response->setCode(401);
                $response->render();
                return;
            }
        }
        $router = $this->getRouterContainer();
        
        if (!$router->getMatchedRouterResource($request::getMethod(), $requestUrl)) {
            $response->setCode(404);
        } else {
            try  {
                $actionResponse = $this->executeResoruceAction(
                    $router->getMatchedController(),
                    $router->getMatchedAction(),
                    $router->getMatchedRouteParameters()
                );
                
                if ($actionResponse === false) {
                    $response->setCode(404);
                } else {
                    $response->setBody($actionResponse);
                }
            } catch(\APIJet\CustomException $e) {
                $response->setCode($e->getHttpCode());
                $response->setBody($e->getErrorBody());
            } catch(\Exception $e) {
                $response->setCode(500);
            }
        }
        $response->render();
    }
    
    /**
     * @return response of executed action or false in case it doesn't exist
     * @param string $controller
     * @param string $action
     * @param string $parameters
     */
    private function executeResoruceAction($controller, $action, $parameters)
    {
        $controller = ucfirst($controller);
        $action = strtolower($this->getRequestContainer()->getMethod()).'_'.$action;
        $controller = 'Controllers\\'.$controller;
        
        // Check if class exist
        if (!class_exists($controller)) {
            return false;
        }
        
        $controllerInstance = new $controller($this);
        
        // Check if action exist
        if (!method_exists($controllerInstance, $action)) {
            return false;
        }
        
        // Check if it's a callable method
        if (!is_callable([$controllerInstance, $action])) {
            return false;
        }
        
        return (array) call_user_func_array(array($controllerInstance, $action), $parameters);
    }
}

