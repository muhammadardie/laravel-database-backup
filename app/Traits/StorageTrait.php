<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

trait StorageTrait
{
    public function customFilesystem()
    {
        return 'dynamicDisk'; // name of custom filesystem configuration
    }

    public function upload($insertedRecord, $files,$oldName=null)
    {
        $upload  = true;
        $primKey = $this->model->getKeyName();
        // if there's any files
        if(!empty($files)){
            foreach ($files as $key => $file) {
                if ($file['file']) {
                    $image           = $file['file'];
                    $destinationPath = $file['path'];
                    $imagename       = $file['name'].'_'. $insertedRecord->$primKey . '_'. time() . '.' . $image->getClientOriginalExtension();
                    
                    if (array_key_exists("field",$file)){
                        $this->update([$file['field'] => $imagename], $insertedRecord->$primKey);
                    }

                    // upload files
                    $upload = $this->storeFile($image, $destinationPath, $imagename, $oldName);
                }
            }

        }

        return $upload;
    }

    public function storeFile($image, $destinationPath, $imagename, $oldName)
    {
        $oldFile = Storage::exists($destinationPath.'/'.$oldName);

        if($oldFile) Storage::delete($destinationPath.'/'.$oldName);

        Storage::putFileAs($destinationPath,$image, $imagename);

        return Storage::exists($destinationPath.'/'.$imagename);
    }

    public function deleteFile($path, $filesystem=null)
    {
        if($filesystem) {
            $this->setFilesystem($filesystem);
            $exist  = Storage::disk($this->customFilesystem())->exists($path);

            if($exist) Storage::disk($this->customFilesystem())->delete($path);
        } else {
            $exist  = Storage::exists($path);

            if($exist) Storage::delete($path);
        }

        return $exist;
    }

    /**
    * Download backup from corresponding filesystem
    * @param $path
    * @param $filesystem custom filesystem
    * @return file download of backup file
    */
    public function downloadFile($path, $filesystem=null)
    {
        if($filesystem) {
            $this->setFilesystem($filesystem);
            $download = Storage::disk($this->customFilesystem())->download($path);
        } else {
            $download = Storage::download($path);
        }

        return $download;
    }

    /**
    * get file size of backup file from corresponding disk
    * @param $backupId
    * @return file size of backup file
    */
    public function fileSize($path, $filesystem=null)
    {
        if($filesystem) {
            $this->setFilesystem($filesystem);
            $size = Storage::disk($this->customFilesystem())->size($path);
        } else {
            $size = Storage::size($path);
        }

        return \Helper::bytesToHuman($size);
    }

    /**
    * check storage disk can be used
    * @param $storage
    * @return status storage can be used (true/false)
    */
    public function checkStorage($storage)
    {
        try {
            $res = ['status' => true, 'msg' => 'ok'];

            $this->setFilesystem($storage);
            Storage::disk($this->customFilesystem())->files('./');

        } catch (\Exception $e) {
            $res = ['status' => false, 'msg' =>$e->getMessage()];
        }

        return $res; 
    }

    /**
    * check file exists in storage disk
    * @param $fileName file name stored backup file
    * @return status store (success -> array file name stored, failed -> null)
    */
    public function checkFile($fileName)
    {
        return Storage::disk($this->customFilesystem())->exists($fileName);
    }

    /**
    * Store backup file to disk and delete backup file in public dir
    * @param $fileName file name stored backup file
    * @return status store (success -> array file name stored, failed -> null)
    */
    public function storeBackupFile($filesystem, $fileName)
    {
        try {
            
            $this->setFilesystem($filesystem);

            $store = Storage::disk($this->customFilesystem())
                         ->putFileAs('',
                            new File(public_path($fileName)), 
                            $fileName
                          );

            // delete file after stored in disk
            unlink(public_path($fileName));

        } catch (\Exception $e) {

            // error message
            return $e->getMessage();
        }

        return $this->checkFile($fileName);
    }

    /**
    * Set configuration of custom disk filesystem for localhost or sftp 
    * @param $record from table disk
    */
    public function setFilesystem($disk)
    {
        if($disk->host === 'localhost') {
            \Config::set('filesystems.disks.' . $this->customFilesystem(),
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
            \Config::set('filesystems.disks.' . $this->customFilesystem(),
                [
                    'driver'   => 'sftp',
                    'host'     => $disk->host,
                    'username' => $disk->username,
                    'password' => $disk->password,
                    'port'     => $disk->port,
                    'root'     => $disk->path,
                ]
            );
        } 
    }

}
