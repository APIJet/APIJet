<?php 

namespace Helper;

class Arr
{
    public static function extract(array $from, array $needItems, $default = null)
    {
        $foundItem = [];
        foreach($needItems as $needItem) {
            $foundItem[$needItem] = isset($from[$needItem]) ? $from[$needItem] : $default;
        }
        
        return $foundItem;
    }
}