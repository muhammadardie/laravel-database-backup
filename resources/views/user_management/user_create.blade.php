@extends('layouts.app')
@section('content')

<div class="content">
  <div class="page-inner">
    <div class="page-header">
      <ul class="breadcrumbs">
        <li class="nav-home">
          <a>
            <i class="fas fa-user-cog"></i>
          </a>
        </li>
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="{{ route('user.index') }}">User</a>
        </li>
        <li class="separator">
          <i class="flaticon-right-arrow"></i>
        </li>
        <li class="nav-item">
          <a href="{{ route('user.create') }}">Add User</a>
        </li>
      </ul>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <div class="card-title"> Add User</div>
          </div>
          <form class="form-submit" id="user-create-form" role="form" action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
          @csrf
            <div class="card-body">
              <div class="row">
                <div class="col-md-12">
                  @include('partials.form-input', [
                    'title'       => __('Name'),
                    'type'        => 'text',
                    'name'        => 'name',
                    'placeholder' => true,
                    'required'    => true
                  ])
                  @include('partials.form-input', [
                    'title'       => __('Email'),
                    'type'        => 'email',
                    'name'        => 'email',
                    'placeholder' => true,
                    'required'    => true
                  ])
                  @include('partials.form-input', [
                    'title'       => __('Password'),
                    'type'        => 'password',
                    'name'        => 'password',
                    'placeholder' => true,
                    'required'    => true
                  ])     
                  @include('partials.form-input', [
                    'title'       => __('Password Confirmation'),
                    'type'        => 'password',
                    'name'        => 'password_confirmation',
                    'placeholder' => true,
                    'required'    => true
                  ])
                  @include('partials.form-file', [
                    'title'       => __('Avatar'),
                    'name'        => 'photo',
                    'required'    => true
                  ])
               </div>
              </div>
            </div>
            @include('partials.form-submit')
          </form>
        </div>
      </div>
    </div>
  </div>
{!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#user-create-form') !!}
@endsection