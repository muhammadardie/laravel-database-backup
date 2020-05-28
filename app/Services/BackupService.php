<?php

namespace App\Services;

use App\Models\Backup;
use App\Services\SourceService;
use App\Services\DiskService;
use Illuminate\Http\File;

class BackupService extends BaseService
{
    public function __construct(Backup $backup, SourceService $sourceService, DiskService $diskService)
    {
        $this->model         = $backup;
        $this->sourceService = $sourceService;
        $this->diskService   = $diskService;
        $this->fileSystem    = 'dynamicDisk'; // name of custom filesystem configuration
        $this->pgdump        = \Config::get('backup.pgdump');
    }

    /**
    * Backup database to temporary directory, store to disk (sftp), delete file in temporary directory and store record
    * @param $request
    * @return result from store record
    */
    public function createBackup($request)
    {
        $source = $this->sourceService->show($request->source_id);
        $disk   = $this->diskService->show($request->disk_id);

        // Backup database to temporary directory
        $backupDatabase = $this->backupDatabase([
            'db_name'     => $request->database,
            'host'        => $source->host,
            'port'        => $source->port,
            'username'    => $source->username,
            'output_file' => $request->name
        ]);

        // store to disk (sftp) and delete file in temporary directory
        $storeBackup = $this->storeBackup($disk, $request->name);
        
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
        $source   = $this->sourceService->show($backup->source_id);
        $disk     = $this->diskService->show($backup->disk_id);
        $fileName = $backup->name;
        
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
        $backup->put('size', $this->getSize($disk, $fileName));

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
        $disk     = $this->diskService->show($backup->disk_id);

        $this->setDisk($disk);

        return \Storage::disk($this->fileSystem)->download('database-backup/'. $backup->name);
    }

    /**
    * Delete backup from corresponding disk
    * @param $backupId
    * @return result of deleted backup record
    */
    public function deleteBackup($backupId)
    {
        $backup   = $this->show($backupId);
        $disk     = $this->diskService->show($backup->disk_id);

        $this->setDisk($disk);
        \Storage::disk($this->fileSystem)->delete('database-backup/'. $backup->name);
        
        return $this->delete($backupId);
    }

    /**
    * get file size of backup file from corresponding disk
    * @param $backupId
    * @return file size of backup file
    */
    public function getSize($disk, $fileName) {
        $this->setDisk($disk);
        
        $size = \Storage::disk($this->fileSystem)->size('database-backup/'. $fileName);
        
        return \Helper::bytesToHuman($size);
    }

    /**
    * Backup database temporary to public path
    * @param $params
    * @return status backup (null if success, empty array if failed)
    */
    public function backupDatabase($params)
    {
        $command = $this->pgdump . ' -d ' .  $params['db_name'] . ' -h ' . $params['host'] . ' -p ' . $params['port'] . ' -U ' . $params['username'] . ' -F custom > ' . $params['output_file'];
        $output  = $this->executeCommand($command);

        return $output;
    }

    /**
    * Store backup file to disk and delete backup file in public dir
    * @param $fileName file name stored backup file
    * @return status store (success -> array file name stored, failed -> null)
    */
    public function storeBackup($disk, $fileName)
    {
        $this->setDisk($disk);

        $store = \Storage::disk($this->fileSystem)
                         ->putFileAs('database-backup', // default directory name
                            new File(public_path($fileName)), 
                            $fileName
                          );
        // delete file after stored in disk
        unlink(public_path($fileName));

        return $store;
    }

    /**
    * Set configuration of custom disk filesystem for localhost or sftp 
    * @param $record from table disk
    */
    public function setDisk($disk)
    {
        if($disk->host === 'localhost') {
            \Config::set('filesystems.disks.' . $this->fileSystem,
                [
                    'driver' => 'local',
                    'root'   => storage_path('app'),
                    'permissions' => [
                        'file' => [
                            'public' => 0664,
                            'private' => 0600,
                        ],
                        'dir' => [
                            'public' => 0775,
                            'private' => 0700,
                        ],
                    ],
                ]
            );
        } else {
            \Config::set('filesystems.disks.' . $this->fileSystem,
                [
                    'driver'   => 'sftp',
                    'host'     => $disk->host,
                    'username' => $disk->username,
                    'password' => $disk->password,

                    // Settings for SSH key based authentication...
                    // 'privateKey' => '/path/to/privateKey',
                    // 'password' => 'encryption-password',

                    // Optional SFTP Settings...
                    'port' => $disk->port,
                    'root' => $disk->path,
                    // 'timeout' => 30,
                ]
            );
        } 
    }

    public function makeDatatableBackup($request)
    {
        if($request->ajax()){
            $sql_no_urut = \Yajra_datatable::get_no_urut('backup_histories.id', $request);
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