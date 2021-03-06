@extends('layouts.app')
@section('content')
<!-- begin:: Content -->
<div class="content">
   <div class="page-inner">
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
               <a>Database</a>
            </li>
            <li class="separator">
               <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
               <a href="{{ route('backup.index') }}">Backup</a>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card">
               <div class="card-header">
                  <button class="btn btn-primary btn-add" data-toggle="modal" data-target="#modal-create-backup">
                    <i class="fa fa-plus"></i>
                      Create Backup
                  </button>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="table" class="display table table-striped table-hover" >
                        <thead>
                           <tr>
                              <th>No</th>
                              <th>Name</th>
                              <th>Source</th>
                              <th>Disk</th>
                              <th>Created At</th>
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
         ajax: '{{ route('backup.ajaxDatatable') }}',
         columns: [
             {data: 'rownum', searchable: false},
             {data: 'name', name: 'backup_histories.name'},
             {data: 'source', name: 'sources.name'},
             {data: 'disk', name: 'disks.name'},
             {data: 'created_at', name: 'backup_histories.created_at'},
             {data: 'action', orderable:false, searchable: false, className: 'text-center', width: "25%"},
         ],
         "drawCallback": function(settings) {
         //
             },            
             pageLength: 10,
             // stateSave: true,
         });
   });
   
</script>
@include('database.backup_create')
@include('database.backup_show')
@include('partials.datatable-delete', ['text' => __('backup'), 'table' => '#table'])
@endsection