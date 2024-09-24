<div class="modal fade" id="modal-create-storage" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-storage-title">Create new storage</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-create-storage" role="form" action="{{ route('storage.store') }}" method="post">
      @csrf
      <div class="modal-body">
        <div class="row">
            @include('partials.form-input', [
              'title'       => __('Name'),
              'type'        => 'text',
              'name'        => 'name',
              'placeholder' => true,
              'required'    => true,
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Host'),
              'type'        => 'text',
              'name'        => 'host',
              'placeholder' => true,
              'required'    => true,
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Username'),
              'type'        => 'text',
              'name'        => 'username',
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
            @include('partials.form-input', [
              'title'       => __('Port'),
              'type'        => 'number',
              'name'        => 'port',
              'placeholder' => true,
              'required'    => true,
              'value'       => 22,
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Path'),
              'type'        => 'text',
              'name'        => 'path',
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
{!! JsValidator::formRequest('App\Http\Requests\StorageRequest', '#form-create-storage') !!}
<script>
  $('#form-create-storage').on('submit', function(e) {
    e.preventDefault();
    
    if( $(this).valid() ){
        blockPage()
      
        $.ajax({
           type: "POST",
           headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           },
           url:'{{ route('storage.store') }}',
           cache: false,
           processData: false,
           data: new FormData($('#form-create-storage')[0]), // The form with the file inputs.
           processData: false,
           contentType: false                    // Using FormData, no need to process data.
        }).done(function(res){
            if(res.status === true)
            {
              successAjax('modal-create-storage', 'saved', res)
            }
            else
            {
              failedAjax(res, res.msg)
            }

            return;
        
        }).fail(function(err){
          failedAjax(err, err.msg)
        });
    }
  });

</script>