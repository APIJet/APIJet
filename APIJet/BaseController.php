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
        return Request::getLimit();
    }
    
    public function getRequestOffset()
    {
        return Request::getOffset();        
    }
    
    public function getInputData()
    {
        return Request::getInputData();
    }
}

