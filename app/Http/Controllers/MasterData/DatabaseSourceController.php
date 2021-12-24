<?php

namespace App\Http\Controllers\MasterData;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\DatabaseSourceRepository;
use App\Http\Requests\DatabaseSourceRequest;

class DatabaseSourceController extends Controller
{
    protected $dbSource;
    
    public function __construct(DatabaseSourceRepository $dbSource)
    {
        $this->dbSource = $dbSource;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = $this->dbSource->type();

        return view ('master_data.database_source.index', compact('type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DatabaseSourceRequest $request)
    {
        return $this->dbSource->saveDbSource($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function show($dbSourceId)
    {
        $source = $this->dbSource->show($dbSourceId);

        return response()->json($source);
    }

    /**
     * Update the specified resource in database source.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function update(DatabaseSourceRequest $request, $dbSourceId)
    {
        return $this->dbSource->updateDbSource($request, $dbSourceId);
    }

    /**
     * Remove the specified resource from database source.
     *
     * @param  int  $dbSourceId
     * @return \Illuminate\Http\Response
     */
    public function destroy($dbSourceId)
    {
       return $this->dbSource->delete($dbSourceId);
    }

    /**
    * Showing list source by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        $route = 'database-source'; // if route not same with table name

        return $this->dbSource->makeDatatable($request, $route);
    }
}
