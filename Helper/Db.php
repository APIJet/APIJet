<?php 

namespace Helper;

use APIJet\Config;
use \PDO;

class Db
{
    private static $instance = null;
    private static $options = [
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ];
    
    private function __construct() {}
    private function __clone() {}
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            $config = Config::getByName('Db');
            
            self::$instance = new PDO('mysql:host='.$config['hostname'].';dbname='.$config['database'], 
                $config['username'], 
                $config['password'],
                self::$options
            );
        }
        
        return self::$instance;
    }
    
    public static function execQuery($query, array $parameters = [])
    {
        $statement = self::getInstance()->prepare($query);
        $statement->execute($parameters);
        return $statement;
    }
    
    public static function getLimitQuery($limit, $offset)
    {
        $limit = (int) $limit;
        $offset = (int) $offset;
        
        return " LIMIT $offset, $limit ";
    }
    
    public static function getLastInsertId()
    {
        return self::getInstance()->lastInsertId();
    }
}
