<?php 

namespace APIJet;

class Response
{
    private function __construct() {}
    private function __clone() {}
    
    private static $code = 200;
    private static $body = [];
    private static $headers = [];
    
    public static function setCode($code) 
    {
        self::$code = $code;
    }
    
    public static function getCode() 
    {
        return self::$code;
    }
    
    public static function setBody($body)
    {
        self::$body = $body;
    }
    
    public static function render()
    {
        self::sendHeaders();
        self::sendBody();
    }
    
    private static function sendHeaders()
    {
        http_response_code(self::getCode());
        header('Content-type: application/json');
    }
    
    private static function sendBody()
    {
        echo json_encode(self::$body);
    }
}