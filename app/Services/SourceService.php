<?php

namespace App\Services;

use App\Models\Source;

class SourceService extends BaseService
{
    public function __construct(Source $source)
    {
        $this->model = $source;
        $this->psql  = \Config::get('backup.psql');
    }

    /**
    * Get list of available database from 
    * @param $fileName file name stored backup file
    * @return status store (success -> array file name stored, failed -> null)
    */
    public function getDatabaseList($sourceId)
    {
        $source  = $this->show($sourceId);
        $command = $this->psql . ' -h ' .  $source->host . ' -p ' . $source->port . ' -U ' . $source->username . ' -l -A -t';
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