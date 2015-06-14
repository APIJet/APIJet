<?php 

namespace APIJet;

class Response
{
    private function __construct() {}
    private function __clone() {}
    
    const CODE_404  = '';
    const CODE_200  = '';
    
    private static $code;
    
    public static function setCode($code) {
        // some validation for this code
        self::$code = $code;
    }
    
    public static function getCode() {
        return self::$code;
    }
    
    public static function render()
    {
        self::sendHeaders();
        self::sendBody();
    }
    
    private static function sendHeaders()
    {
        
    }
    
    private static function sendBody()
    {
        
    }
    
}