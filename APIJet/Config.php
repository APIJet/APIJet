<?php 

namespace APIJet;

class Config
{ 
    private $configStore = [];
    
    public function get($name)
    {
        return $this->configStore[$name];
    }
    
    public function getAll()
    {
        return $this->configStore;
    }

    public function set(array $newConfig)
    {
        $this->configStore = $newConfig + $this->configStore;
    }

    public function isDefined($name)
    {
        return isset($this->configStore[$name]);
    }

    public function loadByJsonFile($filepath)
    {
        $fileContent = file_get_contents($filepath);
        
        if ($fileContent == false) {
            trigger_error("Cannot read configuration file at ".$filepath, E_USER_ERROR);
        }
        
        $newConfig = json_decode($fileContent, true);
        $lastJsonError = json_last_error();
        
        if ($lastJsonError !== JSON_ERROR_NONE) {
            trigger_error("Occured while parsing json, ".json_last_error_msg(), E_USER_ERROR);    
        }
        
        $this->set($newConfig);
    }
}
