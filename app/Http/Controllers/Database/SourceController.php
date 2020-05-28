<?php

namespace App\Http\Controllers\Database;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SourceService;
use App\Http\Requests\SourceRequest;

class SourceController extends Controller
{
    protected $sourceService;
    
    public function __construct(SourceService $sourceService)
    {
        $this->sourceService = $sourceService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = $this->sourceService->getModel()->type();

        return view ('database.source_index', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SourceRequest $request)
    {
        $store = $this->sourceService->store($request->all());
        
        return response()->json(['status' => $store]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function show($sourceId)
    {
        $source = $this->sourceService->show($sourceId);

        return response()->json($source);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function update(SourceRequest $request, $sourceId)
    {
        if($request['password'] == ''){
            unset($request['password']);
        }

        $update = $this->sourceService->update($request->all(), $sourceId);

        return response()->json(['status' => $update]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $sourceId
     * @return \Illuminate\Http\Response
     */
    public function destroy($sourceId)
    {
       return $this->sourceService->delete($sourceId);
    }

    /**
    * Showing list source by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        $route = 'source'; // if route not same with table name

        return $this->sourceService->makeDatatable($request, $route);
    }
}
