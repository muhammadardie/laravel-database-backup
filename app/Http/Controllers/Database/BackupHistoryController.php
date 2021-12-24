<?php

namespace App\Http\Controllers\Database;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\{ BackupHistoryRepository, DatabaseSourceRepository, StorageRepository };
use App\Http\Requests\ManualBackupRequest;

class BackupHistoryController extends Controller
{
    protected $backupHistory;
    protected $storage;
    protected $dbSource;

    public function __construct(BackupHistoryRepository $backupHistory, DatabaseSourceRepository $dbSource, StorageRepository $storage)
    {
        $this->backupHistory = $backupHistory;
        $this->storage       = $storage;
        $this->dbSource      = $dbSource;
    }
    
    /**
     * Display a listing of the rebackup.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dbSources = $this->dbSource->makeDropdown();
        $storage   = $this->storage->makeDropdown();

        return view ('database.history.index', compact('dbSources', 'storage'));
    }

    /**
     * Display the specified backup.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function show($backupId)
    {
        $backup = $this->backupHistory->detailBackup($backupId);

        return response()->json($backup);
    }

    /**
     * Display the specified backup.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function download($backupId)
    {
        return $this->backupHistory->downloadBackup($backupId);
    }

    /**
     * Remove the specified rebackup from storage.
     *
     * @param  int  $backupId
     * @return \Illuminate\Http\Response
     */
    public function destroy($backupId)
    {
       return $this->backupHistory->deleteBackup($backupId);
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

    /**
    * create manual backup
    * @param $request ajax
    * @return json
    */
    public function store(ManualBackupRequest $request)
    {
        return $this->backupHistory->createBackupManual($request);
    }
    
    /**
    * Showing list backup by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        return $this->backupHistory->datatableBackup($request);
    }

}
