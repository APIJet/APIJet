<?php 

namespace Helper\Traits;

use \Helper\Db as DbHelper;

trait Db
{
    private function dbInstance()
    {
        return DbHelper::getInstance();
    }
    
    private function execQuery($query, array $parameters = []) 
    {
        return DbHelper::execQuery($query, $parameters);
    }
    
    private function getSqlLimitByLimits($limit, $offset)
    {
        return DbHelper::getLimitQuery($limit, $offset);
    }
}