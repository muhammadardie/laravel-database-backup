<?php

namespace App\Http\Controllers\Database;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\BackupRepository;
use App\Repositories\SourceRepository;
use App\Repositories\DiskRepository;
use App\Http\Requests\BackupRequest;

class BackupController extends Controller
{
    protected $backupRepository;
    protected $diskRepository;
    protected $sourceRepository;

    public function __construct(BackupRepository $backupRepository, SourceRepository $sourceRepository, DiskRepository $diskRepository)
    {
        $this->backupRepository = $backupRepository;
        $this->diskRepository   = $diskRepository;
        $this->sourceRepository = $sourceRepository;
    }
    
    /**
     * Display a listing of the rebackup.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sources = $this->sourceRepository->makeDropdown();
        $disks   = $this->diskRepository->makeDropdown();

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
        $backup = $this->backupRepository->detailBackup($backupId);

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
        return $this->backupRepository->downloadBackup($backupId);
    }

    /**
     * Remove the specified rebackup from storage.
     *
     * @param  int  $backupId
     * @return \Illuminate\Http\Response
     */
    public function destroy($backupId)
    {
       return $this->backupRepository->deleteBackup($backupId);
    }

    /**
    * Showing source detail by id
    * @param $request ajax
    * @return json
    */
    public function getDatabaseList(Request $request)
    {
        $databases = $this->sourceRepository->getDatabaseList($request->id); // by source id

        return response()->json($databases);
    }

    public function createBackup(BackupRequest $request)
    {
        $status = $this->backupRepository->createBackup($request); // by source id

        return response()->json(['status' => $status]);
    }
    
    /**
    * Showing list backup by datatable
    * @param $request ajax
    * @return json
    */
    public function ajaxDatatable(Request $request)
    {
        return $this->backupRepository->datatableBackup($request);
    }

}
