<?php

namespace App\Services;

class PostgreSqlService extends BaseService
{
    public static function pgdump()
    {
        return \Config::get('backup.pgdump');
    }

    public static function psql()
    {
        return \Config::get('backup.psql');
    }

    /**
    * Backup database temporary to public path
    * @param $params['host', 'db_name', 'port', 'username', 'password']
    * @return status backup (null if success, empty array if failed)
    */
    public static function backup($params)
    {
        $command = self::pgdump() . ' -d ' .  $params['db_name'] . ' -h ' . $params['host'] . ' -p ' . $params['port'] . ' -U ' . $params['username'] . ' -F custom > ' . $params['output_file'];
        putenv("PGPASSWORD=" . $params['password']);
        
        return self::executeCommand($command);
    }

    /**
    * lists database from spesific source and extract string from output into array databases name
    * @param $params['host', 'port', 'username', 'password']
    * @return status backup (null if success, empty array if failed)
    */
    public static function list($params)
    {
        $command = self::psql() . ' -h ' .  $params['host'] . ' -p ' . $params['port'] . ' -U ' . $params['username'] . ' -l -A -t';
        putenv("PGPASSWORD=" . $params['password']);
        $output  = self::executeCommand($command);
        
        $arrayDB = [];
        foreach ($output as $key => $value) {
            $dbName = strtok($value, '|');

            if($dbName !== "postgres" AND $dbName !== "template0" AND $dbName !== "template1" AND $dbName !== "postgres=CTc/postgres") {
                $arrayDB[$dbName] = $dbName;    
            }
            
        } 
        // return $arrayDB;
        return !empty($arrayDB) ? $arrayDB : false;
    }
}