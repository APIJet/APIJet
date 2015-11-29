<?php 

namespace APIJet;

class Request
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    
    private static $inputData = null;
    
    private function __construct() {}
    private function __clone() {}
    
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
    
    public static function getLimit()
    {
        global $app;
        
        if (isset($_GET['limit'])) {
            $limit = (int) $_GET['limit'];
            
            if ($limit > 0) {
                return $limit;
            }
        }
        
        return $app->getConfigContainer()->get('APIJet')[APIJet::DEFAULT_RESPONSE_LIMIT]; 
    }
    
    public static function getOffset()
    {
        if (isset($_GET['offset'])) {
            $offset = (int) $_GET['offset'];
            
            if ($offset < 0) {
                return 0;
            }
            
            return $offset;
        }
        
        return 0;
    }
    
    public static function isАuthorized()
    {
        global $app;
        
        $authorizationCallback = $app->getConfigContainer()->get(APIJet::AUTHORIZATION_CALLBACK);
        
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
