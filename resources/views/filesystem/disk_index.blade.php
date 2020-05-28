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
               <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
               <a>Filesystem</a>
            </li>
            <li class="separator">
               <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
               <a href="{{ route('disk.index') }}">Disk</a>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card">
               <div class="card-header">
                  <button class="btn btn-primary btn-add" data-toggle="modal" data-target="#modal-create-disk">
                    <i class="fa fa-plus"></i>
                      Add Disk
                  </button>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="table" class="display table table-striped table-hover" >
                        <thead>
                           <tr>
                              <th>No</th>
                              <th>Name</th>
                              <th>Host</th>
                              <th>Port</th>
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
         ajax: '{{ route('disk.ajaxDatatable') }}',
         columns: [
             {data: 'rownum', searchable: false, width: '10%'},
             {data: 'name', name: 'disks.name'},
             {data: 'host', name: 'disks.host'},
             {data: 'port', name: 'disks.port'},
             {data: 'action', orderable:false, searchable: false, className: 'text-center'},
         ],
       "drawCallback": function(settings) {
         //
             },            
             pageLength: 10,
             // stateSave: true,
         });
   });
   
</script>
@include('filesystem.disk_create')
@include('filesystem.disk_show')
@include('filesystem.disk_edit')
@include('partials.datatable-delete', ['text' => __('disk'), 'table' => '#table'])
@endsection