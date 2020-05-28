<div class="modal fade" id="modal-create-disk" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-disk-title">Create new disk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-create-disk" role="form" action="{{ route('disk.store') }}" method="post">
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
{!! JsValidator::formRequest('App\Http\Requests\DiskRequest', '#form-create-disk') !!}
<script>
  $('#form-create-disk').on('submit', function(e) {
        e.preventDefault();
        
        if( $(this).valid() ){
            blockPage()
          
            $.ajax({
               type: "POST",
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url:'{{ route('disk.store') }}',
               cache: false,
               processData: false,
               data: new FormData($('#form-create-disk')[0]), // The form with the file inputs.
               processData: false,
               contentType: false                    // Using FormData, no need to process data.
            }).done(function(res){
               successAjax('modal-create-disk', 'saved', res) // (close modal by modal id, message, response)
            }).fail(function(err){
               errorAjax(err, 'saved')
            });
        }
  });
</script>