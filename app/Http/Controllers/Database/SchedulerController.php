<?php

namespace App\Http\Controllers\Database;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\{ SchedulerRepository, DatabaseSourceRepository, StorageRepository };
use App\Http\Requests\SchedulerRequest;

class SchedulerController extends Controller
{
    protected $scheduler;
    protected $storage;
    protected $dbSource;

    public function __construct(SchedulerRepository $scheduler, DatabaseSourceRepository $dbSource, StorageRepository $storage)
    {
        $this->scheduler = $scheduler;
        $this->storage   = $storage;
        $this->dbSource  = $dbSource;
    }
    
    /**
     * Display a listing of the rebackup.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbSources = $this->dbSource->makeDropdown();
        $storage = $this->storage->makeDropdown();
        $statusScheduler = $this->scheduler->statusScheduler();

        return view ('database.scheduler.index', compact('dbSources', 'storage', 'statusScheduler'));
    }

    /**
     * Display the specified backup.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function show($schedulerId)
    {
        $schedule = $this->scheduler->show($schedulerId);
        $schedule->storageName = $schedule->storage->name;
        $schedule->sourceName = $schedule->dbSource->name;
        $schedule->listDatabase = implode(", ",json_decode($schedule->database));
        $schedule->status = $schedule->running ? 'Running' : 'Stopped';
        $schedule->availableDatabase = $this->dbSource->getDatabaseListBySource($schedule->database_source_id);
        
        return $schedule;
    }

    /**
     * Remove the specified rebackup from storage.
     *
     * @param  int  $schedulerId
     * @return \Illuminate\Http\Response
     */
    public function destroy($schedulerId)
    {
       return $this->scheduler->delete($schedulerId);
    }

    /**
    * Showing source detail by id
    * @param $request ajax
    * @return json
    */
    public function getDatabaseList(Request $request)
    {
        $databases = $this->dbSource->getDatabaseListBySource($request->id); // by source id

        return response()->json($databases);
    }

    public function store(SchedulerRequest $request)
    {
        $request['database'] = json_encode($request->database);
        $request['user_created'] = \Auth::user()->id;
        $store = $this->scheduler->store($request->all());
        
        return response()->json(['status' => $store]);
    }

    /**
     * Update the specified resource in database source.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function update(SchedulerRequest $request, $scheduleId)
    {
        $request['database'] = json_encode($request->database);
        $update = $this->scheduler->update($request->all(), $scheduleId);

        return response()->json(['status' => $update]);
    }
    
    /**
    * Showing list backup by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        return $this->scheduler->datatableScheduler($request);
    }

}
