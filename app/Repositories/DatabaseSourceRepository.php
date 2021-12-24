<?php

namespace App\Repositories;

use App\Models\DatabaseSource;
use App\Services\PostgreSqlService;

class DatabaseSourceRepository extends BaseRepository
{
    public function __construct(DatabaseSource $source)
    {
        $this->model = $source;
    }

    public function getDatabaseList($dbSource)
    {
        // list databases
        return PostgreSqlService::list([
            'host'        => $dbSource->host,
            'port'        => $dbSource->port,
            'username'    => $dbSource->username,
            'password'    => $dbSource->password
        ]);
    }

    public function getDatabaseListBySource($sourceId)
    {
        $source  = $this->show($sourceId);
        $source->password = $source->hashedPassword;
        
        return $this->getDatabaseList($source);
    }

    public function saveDbSource($request)
    {
        $dbList = $this->getDatabaseList($request);

        if($dbList === false) return ['status' => false, 'msg' => 'Cannot connect to provided database source'];

        $request['password'] = $this->hashPassword($request->password);
        $store = $this->store($request->all());
        
        return ['status' => $store];
    }

    public function updateDbSource($request, $dbSourceId)
    {
        if($request['password'] == '')
        {
            unset($request['password']);
        }
        else
        {
            $dbList = $this->getDatabaseList($request);

            if($dbList === false) return ['status' => false, 'msg' => 'Cannot connect to provided database source'];
            $request['password'] = $this->hashPassword($request->password);
        }

        $update = $this->update($request->all(), $dbSourceId);

        return response()->json(['status' => $update]);
    }
}