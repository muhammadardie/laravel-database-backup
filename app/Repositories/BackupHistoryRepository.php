<?php

namespace App\Repositories;

use App\Models\BackupHistory;
use App\Repositories\{ DatabaseSourceRepository, StorageRepository };
use App\Services\PostgreSqlService;

class BackupHistoryRepository extends BaseRepository
{
    public function __construct(BackupHistory $backupHistory, DatabaseSourceRepository $dbSource, StorageRepository $storage)
    {
        $this->model    = $backupHistory;
        $this->dbSource = $dbSource;
        $this->storage  = $storage;
    }

    public function backupDatabase($dbSource)
    {
        // Backup database as backup file and store in temporary directory (public_path())
        return PostgreSqlService::backup([
            'db_name'     => $dbSource->database,
            'host'        => $dbSource->host,
            'port'        => $dbSource->port,
            'username'    => $dbSource->username,
            'password'    => $dbSource->hashedPassword,
            'output_file' => $dbSource->fileName
        ]);
    }

    /**
    * Backup database to temporary directory, store to storage (sftp), delete file in temporary directory and store record
    * @param $request
    * @return result from store record
    */
    public function createBackupManual($request)
    {
        $source  = $this->dbSource->show($request->database_source_id);
        $source->database = $request->database;
        $source->fileName = $request->filename;

        $storage = $this->storage->show($request->storage_id);
        $storage->password = $storage->hashedPassword;

        $resBackup = $this->backupDatabase($source);

        if($resBackup != TRUE) return [
            'status' => false,
            'msg' => 'Failed to backup database from source'
        ];

        // store to storage (sftp) and delete file in temporary directory (Traits/StorageTrait->storeBackupFile())
        $storeBackup = $this->storeBackupFile($storage, $request->filename);
        if(is_string($storeBackup)) return [
            'status' => false,
            'msg' => 'Failed to store backup file to storage'
        ];

        // store record
        $request['user_created'] = \Auth::user()->id;
        $store = $this->store($request->all());

        return ['status' => true];      
    }

    /**
    * Detail backup
    * @param $backupId
    * @return collection of detail backup {storage, source, database, file name, path, size, created at}
    */
    public function detailBackup($backupId)
    {
        $backup            = $this->show($backupId);
        $source            = $this->dbSource->show($backup->database_source_id);
        $storage           = $this->storage->show($backup->storage_id);
        $storage->password = $storage->hashedPassword;
        $fileName          = $backup->filename;
        
        // if create backup using custom path then using $backup->path
        if($backup->path) {
            $path = $backup->path;
        } 

        // if storage used during backup is localhost then using default path for localhost
        elseif($storage->host === 'localhost') { 
            $path = storage_path('app');
        } 

        // if none above using default path from storage created
        else {
            $path = $storage->path; 
        }

        // set record backup to collection so it can set new key and value 
        $backup   = collect($backup);
        
        $backup->put('path', $path);
        $backup->put('source', $source->name);
        $backup->put('storage', $storage->name);
        $backup->put('size', $this->fileSize($fileName, $storage));

        return $backup;
    } 
    
    /**
    * Download backup from corresponding storage
    * @param $backupId
    * @return file download of backup file
    */
    public function downloadBackup($backupId)
    {
        $backup            = $this->show($backupId);
        $storage           = $this->storage->show($backup->storage_id);
        $storage->password = $storage->hashedPassword;
        $fileName          = $backup->filename;
        
        return $this->downloadFile($fileName, $storage);
    }

    /**
    * Delete backup from corresponding storage
    * @param $backupId
    * @return result of deleted backup record
    */
    public function deleteBackup($backupId)
    {
        $backup            = $this->show($backupId);
        $storage           = $this->storage->show($backup->storage_id);
        $storage->password = $storage->hashedPassword;
        $fileName          = $backup->name;
        $delete            = $this->deleteFile($fileName, $storage);
         
        return $this->delete($backupId);
    }

    public function datatableBackup($request)
    {
        if($request->ajax()){
            $sql_no_urut = $this->getRowNum('backup_histories.id', $request);
            $backup  = $this->model
                          ->select([
                            \DB::raw($sql_no_urut),
                            'backup_histories.id',
                            'backup_histories.filename',
                            'database_sources.name AS source',
                            'storage.name AS storage',
                            'backup_histories.created_at'
                          ])
                          ->join('database_sources', 'database_sources.id', '=', 'backup_histories.database_source_id')
                          ->join('storage', 'storage.id', '=', 'backup_histories.storage_id');

            return \DataTables::of($backup)
                            ->addColumn('action', function ($backup) {
                                $btn_action = '<a data-href="'. route('histories.show', $backup->id) .'"class="btn btn-action cur-p btn-outline-primary btn-show-datatable" title="Detail">
                                                    <span class="fa fa-search"></span></a>&nbsp;&nbsp;';

              
                                    $btn_action .= '<a href="'. route('histories.download', $backup->id) .'"
                                                    class="btn btn-action cur-p btn-outline-primary btn-download-datatable" title="Download">
                                                    <span class="fa fa-download"></span></a>&nbsp;&nbsp;';

                                    $btn_action .= '<a data-href="'. route('histories.destroy', $backup->id) .'"
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