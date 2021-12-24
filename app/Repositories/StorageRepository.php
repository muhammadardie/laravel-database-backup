<?php

namespace App\Repositories;

use App\Models\Storage;

class StorageRepository extends BaseRepository
{
    public function __construct(Storage $storage)
    {
        $this->model = $storage;
    }

    public function saveStorage($request)
    {
        $resStorage = $this->checkStorage($request);

        if($resStorage['status'] === false) return $resStorage;

        $request['password'] = $this->hashPassword($request->password);
        $store = $this->store($request->all());
        
        return response()->json(['status' => $store]);
    }

    public function updateStorage($request, $storageId)
    {
        if($request['password'] == '')
        {
            unset($request['password']);
        }
        else
        {
            $resStorage = $this->checkStorage($request);

            if($resStorage['status'] === false) return $resStorage;
            $request['password'] = $this->hashPassword($request->password);
        }

        $update = $this->update($request->all(), $storageId);

        return response()->json(['status' => $update]);
    }
}