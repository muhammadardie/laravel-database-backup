<?php

namespace App\Repositories;

use DB;
use Yajra\Datatables\Datatables;

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

    // show model which don't have relation with model given 
    public function notIn($relation)
    {
        return $this->model->doesntHave($relation)->orderBy('nama')->pluck('nama', $this->model->getKeyName());
    }

    // show model which don't have relation with model given and exclude id given 
    public function notInExcept($relation, $targetField, $targetId)
    {
        return $this->model->whereDoesntHave($relation, function ($query) use($targetField, $targetId){
                                $query->where($targetField, '!=', $targetId);
                            })
                            ->orderBy('nama')
                            ->pluck('nama', $this->model->getKeyName());
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
        
        if($callback === true){
            return $store;
        } else {
            return $trans;
        }
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
        
        if($callback === true){
            return $record;
        } else {
            return $trans;
        }
    }

    // remove record from the database
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function makeDropdown()
    {
        return $this->model->orderBy('nama')->pluck('nama', $this->model->getKeyName());
    }

    public function makeDatatable($request, $route=NULL)
    {
        if($request->ajax()){
            $tableName   = $this->model->getTable();
            $tableKey    = $this->model->getKeyName();
            $actionId    = method_exists($this->model, 'datatableActionId') ? 
                            $this->model->datatableActionId() : '';
            $rawColumns  = method_exists($this->model, 'rawColumns') ? $this->model->rawColumns() : [];          
            $sql_no_urut = \Yajra_datatable::get_no_urut($tableName.'.'. ($actionId != '' ? $actionId : $tableKey), $request);
            $collection  = $this->model
                                ->select(
                                    array_merge([DB::raw($sql_no_urut)], $this->model->datatableColumns())
                                );
            $collection = method_exists($this->model, 'datatableCond') ? $collection->datatableCond() : $collection;
            $datatable =  Datatables::of($collection)
                            ->addColumn('action', function ($collection) use($route,$tableKey, $actionId, $tableName){
                                $btn_action = '';
                                $route = $route === null ? $tableName : $route;

                                if (in_array("show", $this->model->datatableButtons())) {
                                    $btn_action .= '<a href="'. route($route.'.show', $actionId != '' ? $collection->$actionId : $collection->$tableKey) .'"
                                                    class="btn btn-action cur-p btn-outline-primary" title="Detail">
                                                    <span class="fa fa-search"></span></a>&nbsp;&nbsp;';
                                }

                                if (in_array("edit", $this->model->datatableButtons())) {
                                    $btn_action .= '<a href="'. route($route.'.edit', $actionId != '' ? $collection->$actionId : $collection->$tableKey) .'"
                                                    class="btn btn-action cur-p btn-outline-primary" title="Change">
                                                    <span class="fa fa-edit"></span></a>&nbsp;&nbsp;';
                                }

                                if (in_array("destroy", $this->model->datatableButtons())) {
                                    $btn_action .= '<a href="'. route($route.'.destroy', $actionId != '' ? $collection->$actionId : $collection->$tableKey) .'"
                                                    class="btn btn-action cur-p btn-outline-primary btn-delete-datatable" title="Delete">
                                                    <span class="fa fa-trash"></span></a>';
                                }

                                return $btn_action;
                                
                            })
                            ->addcolumn('created_at', function($collection){
                                return \Helper::tglIndo($collection->created_at); 
                            })
                            ->addcolumn('updated_at', function($collection){
                                return \Helper::tglIndo($collection->updated_at); 
                            });

            if(method_exists($this->model, 'datatableExcTimestamp')){
                if (in_array("created_at", $this->model->datatableExcTimestamp())) {
                    $datatable->removeColumn('created_at');
                }
                
                if (in_array("updated_at", $this->model->datatableExcTimestamp())) {
                    $datatable->removeColumn('updated_at');
                }    
            }

            if(method_exists($this->model, 'makeColumns')){
                $this->model->makeColumns($datatable);        
            }

            return $datatable->rawColumns(array_merge(['action'], $rawColumns)) // to html
                             ->make(true);
        }
    }

    public function uploadFiles($data,$files,$oldName=null)
    {
        $upload  = true;
        $primKey = $this->model->getKeyName();
        // if there's any files
        if(!empty($files)){
            foreach ($files as $key => $file) {
                if ($file['file']) {
                    $image           = $file['file'];
                    $destinationPath = $file['path'];
                    $imagename       = $file['name'].'_'. $data->$primKey . '_'. time() . '.' . $image->getClientOriginalExtension();
                    
                    if (array_key_exists("field",$file)){
                        $this->update([$file['field'] => $imagename], $data->$primKey);
                    }

                    // upload files
                    $upload = $this->storeFiles($image, $destinationPath, $imagename, $oldName);
                }
            }

        }

        return $upload;
    }

    public function storeFiles($image, $destinationPath, $imagename, $oldName)
    {
        $oldFile = \Storage::exists($destinationPath.'/'.$oldName);

        if($oldFile){
            \Storage::delete($destinationPath.'/'.$oldName);
        }

        \Storage::putFileAs($destinationPath,$image, $imagename);

        return \Storage::exists($destinationPath.'/'.$imagename);
    }

    public function deleteFiles($destinationPath, $fileName)
    {
        $file = \Storage::exists($destinationPath.'/'.$fileName);

        if($file){
            \Storage::delete($destinationPath.'/'.$fileName);
        }

        return $file;
    }

    public function arrayDropdown($field)
    {
        $array = [];

        if($field == 'gender'){
            $array = ['Laki-laki' => 'Laki-laki', 'Perempuan'=> 'Perempuan'];
        }elseif($field == 'status_nikah'){
            $array = ['Menikah'=> 'Menikah', 'Belum Menikah' => 'Belum Menikah'];
        }elseif($field == 'aktif'){
            $array = [ 1 => 'Aktif', 0 => 'Tidak Aktif'];
        }

        return $array;
    }
}