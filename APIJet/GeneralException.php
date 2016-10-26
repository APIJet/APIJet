<?php

namespace APIJet;

class GeneralException extends CustomException
{
    public function __construct($errorBody, $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->errorBody = $errorBody;
    }

    public function getHttpCode() 
    {
        return 400;
    }
}