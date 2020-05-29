<?php

namespace App\Http\Controllers\Filesystem;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\DiskRepository;
use App\Http\Requests\DiskRequest;

class DiskController extends Controller
{
    protected $diskRepository;
    
    public function __construct(DiskRepository $diskRepository)
    {
        $this->diskRepository = $diskRepository;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view ('filesystem.disk_index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DiskRequest $request)
    {
        $store = $this->diskRepository->store($request->all());
        
        return response()->json(['status' => $store]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function show($diskId)
    {
        $disk = $this->diskRepository->show($diskId);

        return response()->json($disk);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function update(DiskRequest $request, $diskId)
    {
        if($request['password'] == ''){
            unset($request['password']);
        }

        $update = $this->diskRepository->update($request->all(), $diskId);

        return response()->json(['status' => $update]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $diskId
     * @return \Illuminate\Http\Response
     */
    public function destroy($diskId)
    {
       return $this->diskRepository->delete($diskId);
    }

    /**
    * Showing list disk by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        $route = 'disk'; // if route not same with table name

        return $this->diskRepository->makeDatatable($request, $route);
    }
}
