<?php

namespace App\Repositories;

use App\Models\Source;
use App\Services\PostgreSqlService;

class SourceRepository extends BaseRepository
{
    public function __construct(Source $source)
    {
        $this->model = $source;
    }

    /**
    * Get list of available database from 
    * @param $fileName file name stored backup file
    * @return status store (success -> array file name stored, failed -> null)
    */
    public function getDatabaseList($sourceId)
    {
        $source  = $this->show($sourceId);

        // list databases
        return PostgreSqlService::list([
            'host'        => $source->host,
            'port'        => $source->port,
            'username'    => $source->username,
            'password'    => $source->password
        ]);
    }

}