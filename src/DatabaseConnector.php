<?php

use Simplon\Mysql\Mysql;
use Simplon\Mysql\PDOConnector;

/**
 * Class DatabaseConnector
 *  - connect to database and send back the db connection object for further use
 */

class DatabaseConnector {
    
    /**
     * Create : Mysql connector and return the db connection object
     * @param array $config
     * @return Mysql
     */
    public static function create($config)
    {
        $pdo = new PDOConnector(
            $config['host'],
            $config['user'],
            $config['password'],
            $config['database']
        );
        
        $pdoConn = $pdo->connect('utf8', []); // charset, options
        
        $dbConn = new Mysql($pdoConn);

        return $dbConn;
    }

}