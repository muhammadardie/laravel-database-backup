<?php

namespace App\Http\Controllers\Database;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BackupService;
use App\Services\SourceService;
use App\Services\DiskService;
use App\Http\Requests\BackupRequest;

class BackupController extends Controller
{
    protected $backupService;
    protected $diskService;
    protected $sourceService;

    public function __construct(BackupService $backupService, SourceService $sourceService, DiskService $diskService)
    {
        $this->backupService = $backupService;
        $this->diskService   = $diskService;
        $this->sourceService = $sourceService;
    }
    
    /**
     * Display a listing of the rebackup.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = $this->sourceService->makeDropdown();
        $disks   = $this->diskService->makeDropdown();

        return view ('database.backup_index', compact('sources', 'disks'));
    }

    /**
     * Display the specified backup.
     *
     * @param  int 
     * @return \Illuminate\Http\Response
     */
    public function show($backupId)
    {
        $backup = $this->backupService->detailBackup($backupId);

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
        return $this->backupService->downloadBackup($backupId);
    }

    /**
     * Remove the specified rebackup from storage.
     *
     * @param  int  $backupId
     * @return \Illuminate\Http\Response
     */
    public function destroy($backupId)
    {
       return $this->backupService->deleteBackup($backupId);
    }

    /**
    * Showing list backup by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        return $this->backupService->makeDatatableBackup($request);
    }

    /**
    * Showing source detail by id
    * @param $request ajax
    * @return json
    */
    public function getDatabaseList(Request $request)
    {
        $databases = $this->sourceService->getDatabaseList($request->id); // by source id

        return response()->json($databases);
    }

    public function createBackup(BackupRequest $request)
    {
        $status = $this->backupService->createBackup($request); // by source id

        return response()->json(['status' => $status]);
    }

}
