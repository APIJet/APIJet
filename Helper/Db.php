<?php 

namespace Helper;

use APIJet\APIJet;
use \PDO;

class Db extends PDO
{
    private static $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    
    public function __construct(array $config) 
    {
        parent::__construct('mysql:host='.$config['hostname'].';dbname='.$config['database'],
            $config['username'],
            $config['password'],
            self::$options
        );
    }
    
    public function execQuery($query, array $parameters = [])
    {
        $statement = parent::prepare($query);
        $statement->execute($parameters);
        
        return $statement;
    }
    
    public static function getLimitQuery($limit, $offset)
    {
        $limit = (int) $limit;
        $offset = (int) $offset;
        
        return ' LIMIT '.($offset * $limit).', '.$limit;
    }
}