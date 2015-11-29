<?php 

namespace APIJet;

class Request
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    
    private static $inputData = null;
    
    private $authorizationCallback;
    private $defaultResponseLimit;
    
    public function setAuthorizationCallback($authorizationCallback)
    {
        $this->authorizationCallback = $authorizationCallback;
    }

    public function setDefaultResponseLimit($defaultResponseLimit)
    {
        $this->defaultResponseLimit = $defaultResponseLimit;
    }

    public static function getCleanRequestUrl()
    {
        $rawRequestUrl = $_SERVER["REQUEST_URI"];
        $clearnRequestUrl = strstr($rawRequestUrl, '?', true);
    
        // if it doens't content any GET data
        if ($clearnRequestUrl === false) {
            $clearnRequestUrl = $rawRequestUrl;
        }
    
        // remote first slash
        return substr($clearnRequestUrl, 1);
    }
    
    public static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    public function getLimit()
    {
        if (isset($_GET['limit'])) {
            $limit = (int) $_GET['limit'];
            
            if ($limit > 0) {
                return $limit;
            }
        }
        return $this->defaultResponseLimit; 
    }
    
    public static function getOffset()
    {
        if (isset($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
            
            if ($offset > 0) {
                return $offset;
            }
        }
        return 0;
    }
    
    public function isАuthorized()
    {
        $authorizationCallback = $this->authorizationCallback;
        
        if ($authorizationCallback === null) {
            return true;
        }
        
        return (bool) $authorizationCallback();
    }
    
    public static function getInputData()
    {
        if (self::$inputData === null) {
    
            $inputData = [];
            $rawInput = file_get_contents('php://input');
    
            if (!empty($rawInput)) {
                mb_parse_str($rawInput, $inputData);
            }
    
            self::$inputData = $inputData;
        }
    
        return self::$inputData;
    }
}
