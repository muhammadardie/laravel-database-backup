<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\StorageRepository;
use App\Http\Requests\StorageRequest;
use Illuminate\Support\Facades\Crypt;

class StorageController extends Controller
{
    protected $storage;
    
    public function __construct(StorageRepository $storage)
    {
        $this->storage = $storage;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('master_data.storage.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorageRequest $request)
    {
        return $this->storage->saveStorage($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function show($storageId)
    {
        $disk = $this->storage->show($storageId);

        return response()->json($disk);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function update(StorageRequest $request, $storageId)
    {
        return $this->storage->updateStorage($request, $storageId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $storageId
     * @return \Illuminate\Http\Response
     */
    public function destroy($storageId)
    {
       return $this->storage->delete($storageId);
    }

    /**
    * Showing list disk by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        return $this->storage->makeDatatable($request);
    }
}
