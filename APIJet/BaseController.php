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
}

