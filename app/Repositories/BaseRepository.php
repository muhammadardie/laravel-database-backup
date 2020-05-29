<?php

namespace App\Repositories;

use DB;
use App\Services\{ StorageService, DatatableService };

abstract class BaseRepository
{
    protected $model;
    
    // Get the associated model
    public function getModel()
    {
        return $this->model;
    }

    // Get all instances of model
    public function all()
    {
        return $this->model->all();
    }

    // Get relation of model
    public function with($relation)
    {
        return $this->model->with($relation);
    }
    
    // show the record with the given id
    public function show($id)
    {
        return $this->model->findOrFail($id);
    }

    // Get relations of model for the determined record
    public function withShow($relations,$id)
    {
        return $this->model->with($relations)->findOrFail($id);
    }

    // create a new record in the database
    public function store(array $data, $callback=false)
    {
        DB::beginTransaction();
        $trans = false;

        try {
            
            $store = $this->model->create($data);

            DB::commit();
            $trans = true;
        } catch (\Exception $e) {
            DB::rollback();

            // error page
            abort(403, $e->getMessage());
        }
        
        return $callback ? $store : $trans;
    }

    // update record in the database
    public function update(array $data, $id, $callback = false)
    {
        DB::beginTransaction();
        $trans = false;

        try {
            
            $record = $this->show($id);
            $update = $record->update($data);

            DB::commit();
            $trans = true;
        } catch (\Exception $e) {
            DB::rollback();
            
            // error page
            abort(403, $e->getMessage());
        }
        
        return $callback ? $record : $trans;
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function makeDropdown()
    {
        return $this->model->orderBy('name')->pluck('name', $this->model->getKeyName());
    }

    public function upload($insertedRecord, $files,$oldName=null)
    {
        $upload  = true;
        $primKey = $this->model->getKeyName();
        // if there's any files
        if(!empty($files)){
            foreach ($files as $key => $file) {
                if ($file['file']) {
                    $image           = $file['file'];
                    $destinationPath = $file['path'];
                    $imagename       = $file['name'].'_'. $insertedRecord->$primKey . '_'. time() . '.' . $image->getClientOriginalExtension();
                    
                    if (array_key_exists("field",$file)){
                        $this->update([$file['field'] => $imagename], $insertedRecord->$primKey);
                    }

                    // upload files
                    $upload = StorageService::storeFile($image, $destinationPath, $imagename, $oldName);
                }
            }

        }

        return $upload;
    }

    public function makeDatatable($request, $route=null)
    {
        return DatatableService::create($this->model, $request, $route);
    }
}