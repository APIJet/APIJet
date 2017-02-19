<?php 

namespace APIJet;

abstract class BaseController 
{
    /**
     * @var APIJet
     */
    private $app;
    
    public function __construct($app)
    {
        $this->app = $app;
    }
    
    /**
     * @return APIJet
     */
    public function getApp()
    {
        return $this->app;
    }
    
    public function setResponseCode($code)
    {
        $this->app->getResponseContainer()->setCode($code);
    }
    
    public function getResponseCode()
    {
        return $this->app->getResponseContainer()->getCode();
    }
    
    public function getRequestLimit()
    {
        return $this->app->getRequestContainer()->getLimit();
    }
    
    public function getRequestOffset()
    {
        return $this->app->getRequestContainer()->getOffset();
    }
    
    public function getInputData()
    {
        return $this->app->getRequestContainer()->getInputData();
    }

    public function getConfig($name)
    {
        return $this->app->getConfigContainer()->get($name);
    }
}

