<div class="modal fade" id="modal-edit-storage" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-storage-title">Edit storage</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-edit-storage" role="form" action="{{ route('storage.store') }}" method="post">
      <input type="hidden" name="_method" value="PUT">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
{!! JsValidator::formRequest('App\Http\Requests\StorageRequest', '#form-edit-storage') !!}
<script>
// get data from action button in datatable then pass into modal
$('body').on('click', '.btn-edit-datatable', function(e) {
    e.preventDefault();
    blockPage()

    let url = $(this).attr('data-href');
    // set form action for submit
    $('#form-edit-storage').attr('action', url)

    // ajax for get modal content
    $.ajax({
        url: url,
        success: function(res) {
            // destroy and re-initialize fileinput with initialpreview image
            $('#form-edit-storage input[name=name]').val(res.name)
            $('#form-edit-storage input[name=host]').val(res.host)
            $('#form-edit-storage input[name=username]').val(res.username)
            $('#form-edit-storage input[name=port]').val(res.port)
            $('#form-edit-storage input[name=path]').val(res.path)
            unblockPage()
            $('#modal-edit-storage').modal('toggle');
        },
        error: function(err) {
            unblockPage()
            $('#modal-edit-storage').modal('toggle');
            console.log(err)
        },
        dataType: 'JSON'
    });

});

$('#form-edit-storage').on('submit', function(e) {

    $("#form-edit-storage input[name=password]").rules().laravelValidation.splice(0, 1);

    if ($(this).valid()) {
        blockPage()

        $.ajax({
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: $(this).attr('action'),
            cache: false,
            processData: false,
            data: new FormData($('#form-edit-storage')[0]), // The form with the file inputs.
            processData: false,
            contentType: false // Using FormData, no need to process data.
        }).done(function(res) {
            if(res.status === true)
            {
              successAjax('modal-edit-storage', 'updated', res)
            }
            else
            {
              failedAjax(res, res.msg)
            }

            return;
        }).fail(function(err) {
            failedAjax(err, err.msg)
        });

    }
    // prevent bubbling event
    e.preventDefault();
    e.stopImmediatePropagation();
});

</script>