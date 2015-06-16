<?php 

namespace APIJet;

abstract class BaseController 
{
    public function setResponseCode($code)
    {
        Response::setCode($code);
    }
    
    public function getResponseCode()
    {
        return Response::getCode();
    }
    
    public function getRequestLimit()
    {
        return Request::getLimit();
    }
    
    public function getRequestOffset()
    {
        return Request::getOffset();        
    }
}

