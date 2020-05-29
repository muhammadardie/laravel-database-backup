<?php

namespace App\Repositories;

use App\Models\Backup;
use App\Repositories\SourceRepository;
use App\Repositories\DiskRepository;
use App\Services\{ PostgreSqlService, StorageService, DatatableService };

class BackupRepository extends BaseRepository
{
    public function __construct(Backup $backup, SourceRepository $sourceRepository, DiskRepository $diskRepository)
    {
        $this->model             = $backup;
        $this->sourceRepository  = $sourceRepository;
        $this->diskRepository    = $diskRepository;
    }

    /**
    * Backup database to temporary directory, store to disk (sftp), delete file in temporary directory and store record
    * @param $request
    * @return result from store record
    */
    public function createBackup($request)
    {
        $source = $this->sourceRepository->show($request->source_id);
        $disk   = $this->diskRepository->show($request->disk_id);

        // Backup database to temporary directory
        $backupDatabase = PostgreSqlService::backup([
            'db_name'     => $request->database,
            'host'        => $source->host,
            'port'        => $source->port,
            'username'    => $source->username,
            'password'    => $source->password,
            'output_file' => $request->name
        ]);

        // store to disk (sftp) and delete file in temporary directory
        $storeBackup = StorageService::storeBackup($disk, $request->name);
        
        // store record
        $request['user_created'] = \Auth::user()->id;
        $store                   = $this->store($request->all());

        return $store;        
    }

    /**
    * Detail backup
    * @param $backupId
    * @return collection of detail backup {disk, source, database, file name, path, size, created at}
    */
    public function detailBackup($backupId)
    {
        $backup   = $this->show($backupId);
        $source   = $this->sourceRepository->show($backup->source_id);
        $disk     = $this->diskRepository->show($backup->disk_id);
        $fileName = 'database-backup/' . $backup->name;
        
        // if create backup using custom path then using $backup->path
        if($backup->path) {
            $path = $backup->path;
        } 

        // if disk used during backup is localhost then using default path for localhost
        elseif($disk->host === 'localhost') { 
            $path = storage_path('app\database-backup');
        } 

        // if none above using default path from disk created
        else {
            $path = $disk->path; 
        }

        // set record backup to collection so it can set new key and value 
        $backup   = collect($backup);
        
        $backup->put('path', $path);
        $backup->put('source', $source->name);
        $backup->put('disk', $disk->name);
        $backup->put('size', StorageService::fileSize($fileName, $disk));

        return $backup;
    } 
    
    /**
    * Download backup from corresponding disk
    * @param $backupId
    * @return file download of backup file
    */
    public function downloadBackup($backupId)
    {
        $backup   = $this->show($backupId);
        $disk     = $this->diskRepository->show($backup->disk_id);
        $fileName = 'database-backup/' . $backup->name;

        return StorageService::downloadFile($fileName, $disk);
    }

    /**
    * Delete backup from corresponding disk
    * @param $backupId
    * @return result of deleted backup record
    */
    public function deleteBackup($backupId)
    {
        $backup   = $this->show($backupId);
        $disk     = $this->diskRepository->show($backup->disk_id);
        $fileName = 'database-backup/' . $backup->name;
        $delete   = StorageService::deleteFile($fileName, $disk);
         
        return $this->delete($backupId);
    }

    public function datatableBackup($request)
    {
        if($request->ajax()){
            $sql_no_urut = DatatableService::getRowNum('backup_histories.id', $request);
            $backup  = $this->model
                          ->select([
                            \DB::raw($sql_no_urut),
                            'backup_histories.id',
                            'backup_histories.name',
                            'sources.name AS source',
                            'disks.name AS disk',
                            'backup_histories.created_at'
                          ])
                          ->join('sources', 'sources.id', '=', 'backup_histories.source_id')
                          ->join('disks', 'disks.id', '=', 'backup_histories.disk_id');

            return \DataTables::of($backup)
                            ->addColumn('action', function ($backup) {
                                $btn_action = '<a data-href="'. route('backup.show', $backup->id) .'"class="btn btn-action cur-p btn-outline-primary btn-show-datatable" title="Detail">
                                                    <span class="fa fa-search"></span></a>&nbsp;&nbsp;';

              
                                    $btn_action .= '<a href="'. route('backup.download', $backup->id) .'"
                                                    class="btn btn-action cur-p btn-outline-primary btn-download-datatable" title="Download">
                                                    <span class="fa fa-download"></span></a>&nbsp;&nbsp;';

                                    $btn_action .= '<a data-href="'. route('backup.destroy', $backup->id) .'"
                                                    class="btn btn-action cur-p btn-outline-primary btn-delete-datatable" title="Delete">
                                                    <span class="fa fa-trash"></span></a>';
    
                                return $btn_action;
                            })
                            ->addcolumn('created_at', function($backup){
                                return \Helper::tglIndo($backup->created_at); 
                            })
                            ->rawColumns(['action']) // to html
                            ->make(true);
        }
    }
}