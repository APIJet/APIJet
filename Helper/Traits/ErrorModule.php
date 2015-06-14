<?php 

namespace Helper\Traits;

trait ErrorModule
{
    private $errorCode = null;
    
    public function getErrorCode()
    {
        return $this->errorCode;
    }
    
    public function getErrorMessage()
    {
        return self::$errorList[$this->getErrorCode()];
    }
    
    public function hasError()
    {
        return ($this->errorCode !== null);
    }
    
    protected function setError($newError) 
    {
        $this->errorCode = $newError;
    }
    
    public function getErrorInfo()
    {
        return [
            'error_code' => $this->getErrorCode(),
            'error_message' => $this->getErrorMessage(),
        ];
    }
}

