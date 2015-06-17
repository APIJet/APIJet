<?php 

namespace Helper\Traits;

use \Helper\Db as DbHelper;

trait Db
{
    private static function dbInstance()
    {
        return DbHelper::getInstance();
    }
    
    private static function execQuery($query, array $parameters = []) 
    {
        return DbHelper::execQuery($query, $parameters);
    }
    
    private static function getSqlLimitByLimits($limit, $offset)
    {
        return DbHelper::getLimitQuery($limit, $offset);
    }
    
    private static function getLastInsertId()
    {
        return DbHelper::getLastInsertId();
    }
}