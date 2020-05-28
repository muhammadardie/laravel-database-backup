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
               <a>User Management</a>
            </li>
            <li class="separator">
               <i class="flaticon-right-arrow"></i>
            </li>
            <li class="nav-item">
               <a href="{{ route('user.index') }}">User</a>
            </li>
         </ul>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="card">
               <div class="card-header">
                  <button class="btn btn-primary btn-add" data-toggle="modal" data-target="#modal-create-user">
                    <i class="fa fa-plus"></i>
                      Add User
                  </button>
               </div>
               <div class="card-body">
                  <div class="table-responsive">
                     <table id="table" class="display table table-striped table-hover" >
                        <thead>
                           <tr>
                              <th>No</th>
                              <th>Name</th>
                              <th>Email</th>
                              <th>Role</th>
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
         ajax: '{{ route('user.ajaxDatatable') }}',
         columns: [
             {data: 'rownum', searchable: false},
             {data: 'name', name: 'users.name'},
             {data: 'email', name: 'users.email'},
             {data: 'role', name: 'users.role'},
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
@include('user_management.user_create')
@include('user_management.user_show')
@include('user_management.user_edit')
@include('partials.datatable-delete', ['text' => __('user'), 'table' => '#table'])
@endsection