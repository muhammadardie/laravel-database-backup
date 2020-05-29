<?php

namespace App\Services;

class MySqlService extends BaseService
{
    public function __construct()
    {
        $this->pgdump = \Config::get('backup.pgdump');
        $this->psql   = \Config::get('backup.psql');
    }

    /**
    * Backup database temporary to public path
    * @param $params['host', 'db_name', 'port', 'username', 'password']
    * @return status backup (null if success, empty array if failed)
    */
    public static function backup($params)
    {
        $command = $this->pgdump . ' -d ' .  $params['db_name'] . ' -h ' . $params['host'] . ' -p ' . $params['port'] . ' -U ' . $params['username'] . ' -F custom > ' . $params['output_file'];
        putenv("PGPASSWORD=" . $params['password']);
        $output  = $this->executeCommand($command);

        return $output;
    }

    /**
    * lists database from spesific source and extract string from output into array databases name
    * @param $params['host', 'port', 'username', 'password']
    * @return status backup (null if success, empty array if failed)
    */
    public static function list($params)
    {
        $command = $this->psql . ' -h ' .  $params['host'] . ' -p ' . $params['port'] . ' -U ' . $params['username'] . ' -l -A -t';
        putenv("PGPASSWORD=" . $params['password']);
        $output  = $this->executeCommand($command);
        
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