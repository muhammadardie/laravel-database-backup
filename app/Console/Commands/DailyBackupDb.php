<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\{ SchedulerRepository, DatabaseSourceRepository, StorageRepository, BackupHistoryRepository };
use DB;
use Psr\Log\LoggerInterface as Log;

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
    public function __construct(SchedulerRepository $scheduler, DatabaseSourceRepository $dbSource, StorageRepository $storage, BackupHistoryRepository $backupRepo, Log $log)
    {
        parent::__construct();
        $this->scheduler  = $scheduler; 
        $this->dbSource   = $dbSource;
        $this->storage    = $storage;
        $this->backupRepo = $backupRepo;
        $this->log        = $log;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->log->info('Scheduler start running at  '. date('d-m-Y H:i:s'));

        DB::beginTransaction();
        $trans = 'failed';

        try {

            $schedules = $this->scheduler->where('running', true)->get();

            foreach($schedules as $schedule) 
            {
                $this->log->info('Schedule "'. $schedule->name .'" starting at  '. date('d-m-Y H:i:s'));

                $autoPruneDay = $schedule->auto_prune_day;
                $source       = $this->dbSource->show($schedule->database_source_id);
                $databases    = json_decode($schedule->database);

                foreach($databases as $database)
                {
                    try {
                        // delete all database backup below "auto_prune_day"
                        if($autoPruneDay)
                        {
                            $this->log->info('Auto Prune day set for "'. $autoPruneDay .' day"');
                            
                            $backupWillPruned = $this->backupRepo
                                                    ->where('database', $database)
                                                    ->whereDate('created_at', '<=', \Carbon\Carbon::now()->subDays($autoPruneDay))
                                                    ->get();
                            if(count($backupWillPruned) > 0)
                            {
                                $this->log->info('Starting Pruning backup database "'. $database .'" which more than '. $autoPruneDay .' day old');
                                foreach($backupWillPruned as $prune)
                                {
                                    $deleted = $this->backupRepo->deleteBackup($prune->id);
                                    if($deleted) $this->log->info($prune->filename .' has been deleted');
                                }
                            }
                            else
                            {
                                $this->log->info('No database "'. $database .'" backup found more than '. $autoPruneDay .' day old');
                            }
                        }

                        $this->log->info('Starting backup database "'. $database .'"');

                        $fileName = $database.'--'.\Carbon\Carbon::now()->format('d-m-Y--H-i-s').'.backup';
                        $source->database = $database;
                        $source->fileName = $fileName;

                        $storage = $this->storage->show($schedule->storage_id);
                        $storage->password = $storage->hashedPassword;

                        $resBackup = $this->backupRepo->backupDatabase($source);

                        if($resBackup != TRUE) {
                            $this->log->error('Failed to backup database "'. $database .'" from source - SKIPPING');
                            continue; // Skip to next database
                        }

                        $this->log->info('Backup database "'. $database .'" Finished');

                        $this->log->info('Store backup file database "'. $database .'" to '. $storage->host);
                        
                        $storeBackup = $this->storage->storeBackupFile($storage, $fileName);
                        if(is_string($storeBackup)) {
                            $this->log->error('Failed to store backup file "'. $database .'" to storage');
                            $this->log->error('ERROR: '. $storeBackup);
                            continue; // Skip to next database
                        }
                        
                        $this->log->info('Backup file database "'. $database .'" has been stored at '. $storage->host .' with path '. $storage->path .'/'. $fileName);

                        // store record to backup history
                        $data['database_source_id'] = $schedule->database_source_id;
                        $data['storage_id']         = $schedule->storage_id;
                        $data['scheduler_id']       = $schedule->id;
                        $data['user_created']       = $schedule->user_created;
                        $data['filename']           = $fileName;
                        $data['database']           = $database;
                        $data['backup_type']        = 'automatic';

                        $store = $this->backupRepo->store($data);
                        
                        $this->log->info('Database "'. $database .'" backup completed successfully');
                        
                    } catch (\Exception $e) {
                        // Log the error but continue with other databases
                        $this->log->error('Exception while backing up database "'. $database .'": '. $e->getMessage());
                        continue;
                    }
                }

                $this->log->info('Schedule "'. $schedule->name .'" running Successfully.');
            }

            DB::commit();
            $trans = 'success';
        } catch (\Exception $e) {
            DB::rollback();

            // error page
            $this->error($e->getMessage());
            $this->log->info('Scheduler has been failed at '. date('d-m-Y H:i:s'));
            $this->log->info('Scheduler stopped running at '. date('d-m-Y H:i:s'));
        }

        $this->log->info('Scheduler '. $trans .' running at '. date('d-m-Y H:i:s'));
    }
}
