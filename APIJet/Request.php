<?php 

namespace APIJet;

class Request
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    
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
}
