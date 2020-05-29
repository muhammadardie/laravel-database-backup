<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;

class StorageService
{
    public static function customFilesystem()
    {
        return 'dynamicDisk'; // name of custom filesystem configuration
    }

    public static function storeFile($image, $destinationPath, $imagename, $oldName)
    {
        $oldFile = Storage::exists($destinationPath.'/'.$oldName);

        if($oldFile) Storage::delete($destinationPath.'/'.$oldName);

        Storage::putFileAs($destinationPath,$image, $imagename);

        return Storage::exists($destinationPath.'/'.$imagename);
    }

    public static function deleteFile($path, $filesystem=null)
    {
        if($filesystem) {
            self::setFilesystem($filesystem);
            $exist  = Storage::disk(self::customFilesystem())->exists($path);

            if($exist) Storage::disk(self::customFilesystem())->delete($path);
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
    public static function downloadFile($path, $filesystem=null)
    {
        if($filesystem) {
            self::setFilesystem($filesystem);
            $download = Storage::disk(self::customFilesystem())->download($path);
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
    public static function fileSize($path, $filesystem=null)
    {
        if($filesystem) {
            self::setFilesystem($filesystem);
            $size = Storage::disk(self::customFilesystem())->size($path);
        } else {
            $size = Storage::size($path);
        }

        return \Helper::bytesToHuman($size);
    }

    /**
    * Store backup file to disk and delete backup file in public dir
    * @param $fileName file name stored backup file
    * @return status store (success -> array file name stored, failed -> null)
    */
    public static function storeBackup($filesystem, $fileName)
    {
        self::setFilesystem($filesystem);

        $store = Storage::disk(self::customFilesystem())
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
    public static function setFilesystem($disk)
    {
        if($disk->host === 'localhost') {
            \Config::set('filesystems.disks.' . self::customFilesystem(),
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
            \Config::set('filesystems.disks.' . self::customFilesystem(),
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