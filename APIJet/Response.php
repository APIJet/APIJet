<?php 

namespace APIJet;

class Response
{
    private $code = 200;
    private $body = [];
    private $headers = [];
    
    public function setCode($code) 
    {
        $this->code = $code;
    }
    
    public function getCode() 
    {
        return $this->code;
    }
    
    public function setBody($body)
    {
        $this->body = $body;
    }
    
    public function render()
    {
        $this->sendHeaders();
        $this->sendBody();
    }
    
    private function sendHeaders()
    {
        http_response_code($this->getCode());
        header('Content-type: application/json');
    }
    
    private function sendBody()
    {
        echo json_encode($this->body);
    }
}