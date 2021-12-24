<div class="modal fade" id="modal-create-database-source" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-source-title">Create new database source</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-create-database-source" role="form" action="{{ route('database-source.store') }}" method="post">
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
            @include('partials.form-select', [
              'title'       => __('Type'),
              'name'        => 'type',
              'data'        => $type,
              'required'    => true,
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Port'),
              'type'        => 'number',
              'name'        => 'port',
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
{!! JsValidator::formRequest('App\Http\Requests\DatabaseSourceRequest', '#form-create-database-source') !!}
<script>
  $('#form-create-database-source').on('submit', function(e) {
        e.preventDefault();
        
        if( $(this).valid() ){
            blockPage()
          
            $.ajax({
               type: "POST",
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url:'{{ route('database-source.store') }}',
               cache: false,
               processData: false,
               data: new FormData($('#form-create-database-source')[0]), // The form with the file inputs.
               processData: false,
               contentType: false                    // Using FormData, no need to process data.
            }).done(function(res){
              if(res.status === true)
              {
                successAjax('modal-create-database-source', 'saved', res) // (close modal by modal id, message, response)
              }
              else
              {
                failedAjax(res, res.msg)
              }
            }).fail(function(err){
               failedAjax(err, err.msg)
            });
        }
  });
</script>