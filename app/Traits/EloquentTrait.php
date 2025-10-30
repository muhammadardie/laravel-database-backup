<?php

namespace App\Traits;

use DB;

trait EloquentTrait
{

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
        // remove proengsoft_jsvalidation from array input if exist
        unset($data['proengsoft_jsvalidation']);

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
        // remove proengsoft_jsvalidation from array input if exist
        unset($data['proengsoft_jsvalidation']);
        
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
}