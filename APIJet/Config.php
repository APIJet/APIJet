<?php 

namespace APIJet;

class Config
{ 
    private $configStore = [];
    
    public function get($name)
    {
        return $this->configStore[$name];
    }
    
    public function set(array $newConfig)
    {
        $this->configStore = $newConfig + $this->configStore;
    }
}