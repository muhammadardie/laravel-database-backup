<div class="modal fade" id="modal-create-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-user-title">Create new user</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-create-user" role="form" action="{{ route('user.store') }}" method="post">
      @csrf
      <div class="modal-body">
        <div class="row">
            @include('partials.form-file', [
              'title'       => __('Avatar'),
              'name'        => 'photo'
            ])
            @include('partials.form-input', [
              'title'       => __('Name'),
              'type'        => 'text',
              'name'        => 'name',
              'placeholder' => true,
              'required'    => true,
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Email'),
              'type'        => 'email',
              'name'        => 'email',
              'placeholder' => true,
              'required'    => true,
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Password'),
              'type'        => 'password',
              'name'        => 'password',
              'placeholder' => true,
              'required'    => true,
              'multiColumn' => true
            ])   
            @include('partials.form-select', [
              'title'    => __('Role'),
              'name'     => 'role',
              'data'     => $roleOpt,
              'required' => true,
              'column'   => 'single',
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Password Confirmation'),
              'type'        => 'password',
              'name'        => 'password_confirmation',
              'placeholder' => true,
              'required'    => true,
              'multiColumn' => true
            ])
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </form>
    </div>
  </div>
</div>
{!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#form-create-user') !!}
<script>
  $('#form-create-user').on('submit', function(e) {
        e.preventDefault();
        
        if( $(this).valid() ){
            blockPage()
          
            $.ajax({
               type: "POST",
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url:'{{ route('user.store') }}',
               cache: false,
               processData: false,
               data: new FormData($('#form-create-user')[0]), // The form with the file inputs.
               processData: false,
               contentType: false                    // Using FormData, no need to process data.
            }).done(function(res){
               successAjax('modal-create-user', 'saved', res) // (close modal by modal id, message, response)
            }).fail(function(err){
               errorAjax(err, 'saved')
            });
        }
  });
</script>