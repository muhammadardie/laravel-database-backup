<?php

namespace App\Repositories;

use App\Models\Scheduler;
use App\Repositories\{ DatabaseSourceRepository, StorageRepository };
use App\Services\PostgreSqlService;

class SchedulerRepository extends BaseRepository
{
    public function __construct(Scheduler $scheduler, DatabaseSourceRepository $dbSource, StorageRepository $storage)
    {
        $this->model    = $scheduler;
        $this->dbSource = $dbSource;
        $this->storage  = $storage;
    }

    public function statusScheduler()
    {
        return [1 => 'Running', 0 => 'Stop'];
    }

    public function datatableScheduler($request)
    {
        if($request->ajax()){
            $sql_no_urut = $this->getRowNum('scheduler.id', $request);
            $scheduler  = $this->model
                              ->select([
                                \DB::raw($sql_no_urut),
                                'scheduler.id',
                                'scheduler.name',
                                'database_sources.name AS source',
                                'storage.name AS storage',
                                'scheduler.running'
                              ])
                              ->join('database_sources', 'database_sources.id', '=', 'scheduler.database_source_id')
                              ->join('storage', 'storage.id', '=', 'scheduler.storage_id');

            return \DataTables::of($scheduler)
                            ->addColumn('action', function ($scheduler) {
                                $btn_action = '<a data-href="'. route('scheduler.show', $scheduler->id) .'"class="btn btn-action cur-p btn-outline-primary btn-show-datatable" title="Detail">
                                                    <span class="fa fa-search"></span></a>&nbsp;&nbsp;';
              
                                    $btn_action .= '<a data-href="'. route('scheduler.show', $scheduler->id) .'"
                                                    class="btn btn-action cur-p btn-outline-primary btn-edit-datatable" title="Change">
                                                    <span class="fa fa-edit"></span></a>&nbsp;&nbsp;';

                                    $btn_action .= '<a data-href="'. route('scheduler.destroy', $scheduler->id) .'"
                                                    class="btn btn-action cur-p btn-outline-primary btn-delete-datatable" title="Delete">
                                                    <span class="fa fa-trash"></span></a>';
    
                                return $btn_action;
                            })
                            ->addColumn('running', function ($scheduler) {
                                $running = ($scheduler->running === TRUE) ? 
                                    "<span class='badge badge-primary'> Running </span>" : 
                                    "<span class='badge badge-secondary'> Stopped </span>";

                                return "<center>".$running."</center>";
                            })
                            ->rawColumns(['action', 'running']) // to html
                            ->make(true);
        }
    }
}