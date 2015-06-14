<?php 

namespace Controller;

class Hello extends \APIJet\BaseController
{
    public function get_world($s1, $s2)
    {
        return ["APIJet" => "Hello, I am RESTful API"];
    }
   
}