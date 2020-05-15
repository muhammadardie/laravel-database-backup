@extends('layouts.app')
@section('content')
<!-- begin:: Content -->
<div class="kt-content kt-grid__item kt-grid__item--fluid" id="kt_content">
    <div class="row">
        
        <div class="col-xl-12">
            <div class="kt-portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                  <div class="kt-portlet__head-label">
                    <span class="kt-portlet__head-icon">
                      <i class="kt-font-brand fa fa-user-cog"></i>
                    </span>
                    <h3 class="kt-portlet__head-title">
                      Detail User
                    </h3>
                  </div>
                </div>
                <form>
                <div class="kt-portlet__body">
                    <div class="row">
                       <div class="col-md-6">
                          @include('partials.form-input', [
                            'title'     => __('Karyawan'),
                            'type'      => 'text',
                            'name'      => 'employee_id', 
                            'value'     => $dataUser->karyawan->nama,
                            'required'  => true,
                            'attribute' => ['disabled']
                          ])
                          @include('partials.form-input', [
                            'title'     => __('Email'),
                            'type'      => 'email',
                            'name'      => 'email',
                            'value'     => $dataUser->email,
                            'required'  => true,
                            'attribute' => ['disabled']
                          ])
                          @include('partials.form-input', [
                            'title'     => __('Terakhir Login'),
                            'type'      => 'text',
                            'name'      => 'last_login',
                            'value'     => \Helper::tglIndo($dataUser->last_login),
                            'attribute' => ['disabled'],
                          ])
                          @include('partials.form-input', [
                            'title'     => __('Tanggal dibuat'),
                            'type'      => 'text',
                            'name'      => 'created_at',
                            'value'     => \Helper::tglIndo($dataUser->created_at),
                            'attribute' => ['disabled'],
                          ])    
                          @include('partials.form-input', [
                            'title'     => __('Tanggal diperbarui'),
                            'type'      => 'text',
                            'name'      => 'updated_at',
                            'value'     => \Helper::tglIndo($dataUser->updated_at),
                            'attribute' => ['disabled'],
                          ])                              
                        </div>
                    </div>
                </div> 
                <div class="kt-portlet__foot">
                  <div class="kt-form__actions">
                    <a href="{{ route('user.index') }}" class="btn btn-outline-secondary">Kembali</a>
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
@endsection
