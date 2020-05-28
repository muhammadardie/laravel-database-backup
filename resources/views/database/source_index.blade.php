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
               <a>Database</a>
            </li>
            <li class="separator">
               <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
               <a href="{{ route('source.index') }}">Source</a>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card">
               <div class="card-header">
                  <button class="btn btn-primary btn-add" data-toggle="modal" data-target="#modal-create-source">
                    <i class="fa fa-plus"></i>
                      Add Source
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
                              <th>Type</th>
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
         ajax: '{{ route('source.ajaxDatatable') }}',
         columns: [
             {data: 'rownum', searchable: false},
             {data: 'name', name: 'sources.name'},
             {data: 'host', name: 'sources.host'},
             {data: 'type', name: 'sources.type'},
             {data: 'action', orderable:false, searchable: false, className: 'text-center', width: "40%"},
         ],
         "drawCallback": function(settings) {
         //
             },            
             pageLength: 10,
             // stateSave: true,
         });
   });
   
</script>
@include('database.source_create')
@include('database.source_show')
@include('database.source_edit')
@include('partials.datatable-delete', ['text' => __('source'), 'table' => '#table'])
<script>
$( document ).ready(function() {
  $('body').on('change', 'select[name=type]', function() {
    const mysqlPort = 3306;
    const psqlPort  = 5432;
    let inputPort   = $(this).parent().next().next().find('input[name=port]'); 

    $(this).val() === 'mysql' ? inputPort.val(mysqlPort) : inputPort.val(psqlPort) 
  })
});
</script>
@endsection