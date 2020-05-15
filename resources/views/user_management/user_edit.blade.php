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
                      Ubah User
                    </h3>
                  </div>
                </div>
                <form class="form-simpan" method="POST" id="user-edit-form" action="{{ route('user.update', $dataUser->user_id) }}">
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="kt-portlet__body">
                    <div class="row">
                       <div class="col-md-6">
                          @include('partials.form-select', [
                            'title'     => __('Karyawan'),
                            'data'      => $empNotUser,
                            'selected'  => $dataUser->karyawan_id,
                            'name'      => 'karyawan_id',
                            'required'  => true,
                          ])
                          @include('partials.form-input', [
                            'title'     => __('Email'),
                            'type'      => 'email',
                            'name'      => 'email',
                            'value'     => $dataUser->email,
                            'required'  => true,
                            'attribute' => ['readonly']
                          ])
                          @include('partials.form-input', [
                            'title'     => __('Password'),
                            'type'      => 'password',
                            'name'      => 'password',
                          ])     
                          @include('partials.form-input', [
                            'title'     => __('Password Confirmation'),
                            'type'      => 'password',
                            'name'      => 'password_confirmation',
                          ])     
                        </div>
                    </div>
                </div> 
                <div class="kt-portlet__foot">
                  <div class="kt-form__actions">
                    @include('partials.form-submit')
                  </div>
                </div>
              </form>
            </div>
        </div>
    </div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#user-edit-form') !!}
<script>
  $(() => {
      $('select[name=karyawan_id]').on('change', function(){
        let thisVal = $(this).val().trim();
        let email   = $('input[name=email]');
        let loading = $('#loading-email');
        let url     = '{{ route('ajax.getEmailEmployee') }}';

        loading.css({'display': 'block'});
        
        // reset
        $(email).val('');
                  
        $.ajax({
            method: 'GET',
            url: url,
            data: {id: thisVal},
            success: function(msg){
                loading.css({'display': 'none'});

                if(msg != ''){
                    $(email).val(msg);
                }
            },
            error: function(err){
                alert(JSON.stringify(err));
                loading.css({'display': 'none'});
            }
        });
      });
  }); 
</script>
@endsection
