<?php 

namespace APIJet;

abstract class BaseController 
{
    public function setResponseCode($code)
    {
        global $app;
        
        $app->getResponseContainer()->setCode($code);
    }
    
    public function getResponseCode()
    {
        global $app;
        
        return $app->getResponseContainer()->getCode();
    }
    
    public function getRequestLimit()
    {
        global $app;
        
        return $app->getRequestContainer()->getLimit();
    }
    
    public function getRequestOffset()
    {
        global $app;
        
        return $app->getRequestContainer()->getOffset();
    }
    
    public function getInputData()
    {
        global $app;
        
        return $app->getRequestContainer()->getInputData();
    }
}

