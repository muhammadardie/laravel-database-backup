@extends('layouts.app')
@section('content')
<!-- begin:: Content -->
<div class="content">
   <div class="page-inner">
    <div class="alert alert-info alert-dismissible fade show" role="alert">
      Storage configuration using <strong>sftp</strong> and <strong>local</strong> driver. For <strong>local</strong> driver use <strong>localhost</strong> for host field
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
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
               <a>Master Data</a>
            </li>
            <li class="separator">
               <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
               <a href="{{ route('storage.index') }}">Storage</a>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card">
               <div class="card-header">
                  <button class="btn btn-primary btn-add" data-toggle="modal" data-target="#modal-create-storage">
                    <i class="fa fa-plus"></i>
                      Add Storage
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
         ajax: '{{ route('storage.ajaxDatatable') }}',
         columns: [
             {data: 'rownum', searchable: false, width: '10%'},
             {data: 'name', name: 'storages.name'},
             {data: 'host', name: 'storages.host'},
             {data: 'port', name: 'storages.port'},
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
@include('master_data.storage.create')
@include('master_data.storage.show')
@include('master_data.storage.edit')
@include('partials.datatable-delete', ['text' => __('storage'), 'table' => '#table'])
@endsection