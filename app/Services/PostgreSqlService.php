<?php

namespace App\Services;

class PostgreSqlService
{
    public static function pgdump()
    {
        return '"'. \Config::get('backup.pgdump') .'"';
    }

    public static function psql()
    {
        return '"'. \Config::get('backup.psql') .'"';
    }

    /**
    * Backup database temporary to public path
    * @param $params['host', 'db_name', 'port', 'username', 'password']
    * @return status backup (null if success, empty array if failed)
    */
    public static function backup($params)
    {
        $outputPath = public_path($params['output_file']);
        $command = self::pgdump() . ' -d ' .  $params['db_name'] . ' -h ' . $params['host'] . ' -p ' . $params['port'] . ' -U ' . $params['username'] . ' -F custom > ' . $outputPath;
        putenv("PGPASSWORD=" . $params['password']);
        exec($command, $output, $status);
        
        return $status === 0; // execute backup is successful only if the $status === 0
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
        
        $arrayDB = [];
        
        exec($command, $output, $status);
        if(is_array($output) && !empty($output)) {
            foreach ($output as $key => $value) {
                $dbName = strtok($value, '|');

                if($dbName !== "postgres" AND $dbName !== "template0" AND $dbName !== "template1" AND $dbName !== "postgres=CTc/postgres") {
                    $arrayDB[$dbName] = $dbName;    
                }
                
            } 
        }

        return !empty($arrayDB) ? $arrayDB : false;
    }
}