<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\{ SchedulerRepository, DatabaseSourceRepository, StorageRepository, BackupHistoryRepository };
use DB;

class DailyBackupDb extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backupDb:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database from scheduler, daily at 00:00 server time'; 

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(SchedulerRepository $scheduler, DatabaseSourceRepository $dbSource, StorageRepository $storage, BackupHistoryRepository $backupRepo)
    {
        parent::__construct();
        $this->scheduler  = $scheduler; 
        $this->dbSource   = $dbSource;
        $this->storage    = $storage;
        $this->backupRepo = $backupRepo;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Scheduler start running at  '. date('d-m-Y H:i:s'));

        DB::beginTransaction();
        $trans = 'failed';

        try {

            $schedules = $this->scheduler->where('running', true)->get();

            foreach($schedules as $schedule) 
            {
                $this->info('Schedule '. $schedule->name .' starting at  '. date('d-m-Y H:i:s'));

                $source    = $this->dbSource->show($schedule->database_source_id);
                $databases = json_decode($schedule->database);

                foreach($databases as $database)
                {
                    $this->info('Starting backup database '. $database);

                    $fileName = $database.'--'.date('d-m-Y--H-i-s').'.backup';
                    $source->database = $database;
                    $source->fileName = $fileName;

                    $storage = $this->storage->show($schedule->storage_id);
                    $storage->password = $storage->hashedPassword;

                    $resBackup = $this->backupRepo->backupDatabase($source);

                    if($resBackup != TRUE) {
                        $this->info('Failed to backup database from source');
                        $this->info('Scheduler stopped running at '. date('d-m-Y H:i:s'));
                        return;
                    }

                    // store to storage (sftp) and delete file in temporary directory (Traits/StorageTrait->storeBackupFile())
                    $storeBackup = $this->storage->storeBackupFile($storage, $fileName);
                    if(is_string($storeBackup)) {
                        $this->info('Failed to store backup file to storage');
                        $this->info('Scheduler stopped running at '. date('d-m-Y H:i:s'));
                        return;
                    }

                    // store record to backup history
                    $data['database_source_id'] = $schedule->database_source_id;
                    $data['storage_id']         = $schedule->storage_id;
                    $data['scheduler_id']       = $schedule->id;
                    $data['user_created']       = $schedule->user_created;
                    $data['filename']           = $fileName;
                    $data['database']           = $database;
                    $data['backup_type']        = 'automatic';

                    $store = $this->backupRepo->store($data);
                }

                $this->info('Schedule '. $schedule->name .'running Successfully.');
            }

            DB::commit();
            $trans = 'success';
        } catch (\Exception $e) {
            DB::rollback();

            // error page
            $this->error($e->getMessage());
            $this->info('Scheduler has been failed at '. date('d-m-Y H:i:s'));
            $this->info('Scheduler stopped running at '. date('d-m-Y H:i:s'));
        }

        $this->info('Scheduler '. $trans .' running at '. date('d-m-Y H:i:s'));
    }
}
