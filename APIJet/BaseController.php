<?php 

namespace APIJet;

abstract class BaseController 
{
    /**
     * @var APIJet
     */
    private static $appInstance;
    
    public function __construct($app)
    {
        self::$appInstance = $app;
    }
    
    /**
     * @return APIJet
     */
    public static function getApp()
    {
        return self::$appInstance;
    }
    
    public function setResponseCode($code)
    {
        self::$appInstance->getResponseContainer()->setCode($code);
    }
    
    public function getResponseCode()
    {
        return self::$appInstance->getResponseContainer()->getCode();
    }
    
    public function getRequestLimit()
    {
        return self::$appInstance->getRequestContainer()->getLimit();
    }
    
    public function getRequestOffset()
    {
        return self::$appInstance->getRequestContainer()->getOffset();
    }
    
    public function getInputData()
    {
        return self::$appInstance->getRequestContainer()->getInputData();
    }

    public function getConfig($name)
    {
        return self::$appInstance->getConfigContainer()->get($name);
    }

    public function isDefinedConfig($name)
    {
        return self::$appInstance->getConfigContainer()->isDefined($name);
    }
}

