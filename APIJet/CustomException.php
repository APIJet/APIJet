<?php

namespace APIJet;

/**
 * @author   Pavel Tashev
 */
class CustomException extends \Exception
{
    protected $errorBody;
    protected $httpCode;

    public function __construct($httpCode, $errorBody, $message = '', $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->errorBody = $errorBody;
        $this->httpCode = $httpCode;
    }

    public function getErrorBody()
    {
        return $this->errorBody;
    }

    public function getHttpCode() 
    {
        return $this->httpCode;
    }
}
