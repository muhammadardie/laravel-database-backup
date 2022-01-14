@extends('layouts.app')
@section('content')
<style>
    .table {
        width: 100% !important;
    }
    .alert .close {
        background: transparent;
        width: 25px;
        height: 25px;
        line-height: 25px;
        top: -10px !important;
        border-radius: 50%;
        font-size: 1.3rem;
    }
    .help-button {
        border-radius: 50%;
        border: 2px solid #202940;
        margin-left: 5px;
        color: #202940;
    }
    .help-button:hover {
        cursor: pointer;
        border: 2px solid #1572e8;
        color: #1572e8;
    }
    .alert-cron {
       background-color: #101520;
    }
    .prune-day-question {
        background-color: white;
        color: #202940;
        padding: 5px;
        border-radius: 16px;
        width: 18px;
        height: 19px;
        font-size: 10px;
        margin-left: 5px;
    }
</style>
<!-- begin:: Content -->
<div class="content">
    <div class="page-inner">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            In order for scheduler keep running you need to create cron task / task scheduler on windows
            <button type="button" class="help-button" title="Click to see details" data-toggle="modal" data-target="#modal-how-to-cron">
                <i class="fas fa-question"></i>
            </button>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modal-how-to-cron" tabindex="-1" role="dialog" aria-labelledby="modal-how-to-cron" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="alert alert-info alert-dismissible fade show alert-cron" role="alert">
                            Scheduler will running an automatic backup on <strong>00.00 at server time</strong>. Set scheduler status to <strong>stopped</strong> or delete it if you want to stop the scheduler
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                          <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="pills-linux-tab" data-toggle="pill" href="#nav-linux" role="tab" aria-controls="pills-linux" aria-selected="true">Linux</a>
                          </li>
                          <li class="nav-item" role="presentation">
                            <a class="nav-link" id="pills-windows-tab" data-toggle="pill" href="#nav-windows" role="tab" aria-controls="pills-windows" aria-selected="false">Windows</a>
                          </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                           <div class="tab-pane fade show active" id="nav-linux" role="tabpanel" aria-labelledby="home-tab">
                             For <strong>Linux users you can follow this intructions</strong>:
                              <br />
                              <code>crontab -e</code>
                              <br />
                              and paste the code below into the file
                              <br />
                              <code>
                                  * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
                              </code>
                           </div>
                           <div class="tab-pane fade" id="nav-windows" role="tabpanel" aria-labelledby="windows-tab">
                              Follow instructions from this <a href="https://www.jdsoftvera.com/how-to-add-laravel-task-schedule-on-windows/" target="_blank">link</a>
                           </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-header">
            <ul class="breadcrumbs">
                <li class="nav-home">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li class="separator">
                    <i class="fltoaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a>Database Backup</a>
                </li>
                <li class="separator">
                    <i class="flaticon-right-arrow"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('scheduler.index') }}">Scheduler</a>
                </li>
            </ul>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <button class="btn btn-primary btn-add" data-toggle="modal" data-target="#modal-create-scheduler">
                            <i class="fa fa-plus"></i>
                            Create Scheduler
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table" class="display table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Name</th>
                                        <th>Database Source</th>
                                        <th>Storage</th>
                                        <th>Running</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
   $(function() {
       var t = $('#table').DataTable({
         processing: true,
         serverSide: true,
         ajax: '{{ route('scheduler.ajaxDatatable') }}',
         columns: [
             {data: 'rownum', searchable: false},
             {data: 'name', name: 'scheduler.name'},
             {data: 'source', name: 'database_sources.name'},
             {data: 'storage', name: 'storage.name'},
             {data: 'running', name: 'scheduler.running'},
             {data: 'action', orderable:false, searchable: false, className: 'text-center', width: "30%"},
         ],
         "drawCallback": function(settings) {
         //
             },            
             pageLength: 10,
             // stateSave: true,
         });
   });
   
   $('body').on('change', 'select[name=database_source_id]', function() {
    blockPage()
    $.ajax({
        url: '{{ route('scheduler.getDatabaseList') }}', //this is your uri
        type: 'GET', //this is your method
        data: { id: this.value },
        dataType: 'json',
        success: function(res){
          unblockPage()
          if(!jQuery.isEmptyObject(res)){
          
            listOpt = "<option value=''></option>"; // reserved for placeholder
            $.each(res, function(i,v){
              listOpt += "<option value='"+ i +"'>"+ v +"</option>";
            });
            
            $("select[name=database\\[\\]]").html(listOpt).select2({ 
              placeholder: "-- Select Database --", 
              width: "50%"
            });
          } else {
            errorAjax(res, 'retrieve')
          }
            
        },
        error: function(err) {
          unblockPage()
          errorAjax(err, 'retrieve')
        }
    });
  })
</script>
@include('database.scheduler.create')
@include('database.scheduler.show')
@include('database.scheduler.edit')
@include('partials.datatable-delete', ['text' => __('scheduler'), 'table' => '#table'])
@endsection