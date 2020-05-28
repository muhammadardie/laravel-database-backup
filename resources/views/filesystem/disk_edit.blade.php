<div class="modal fade" id="modal-edit-disk" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-disk-title">Edit disk</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-edit-disk" role="form" action="{{ route('disk.store') }}" method="post">
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
{!! JsValidator::formRequest('App\Http\Requests\DiskRequest', '#form-edit-disk') !!}
<script>
// get data from action button in datatable then pass into modal
$('body').on('click', '.btn-edit-datatable', function(e) {
    e.preventDefault();
    blockPage()

    let url = $(this).attr('data-href');
    // set form action for submit
    $('#form-edit-disk').attr('action', url)

    // ajax for get modal content
    $.ajax({
        url: url,
        success: function(res) {
            // destroy and re-initialize fileinput with initialpreview image
            $('#form-edit-disk input[name=name]').val(res.name)
            $('#form-edit-disk input[name=host]').val(res.host)
            $('#form-edit-disk input[name=username]').val(res.username)
            $('#form-edit-disk input[name=port]').val(res.port)
            $('#form-edit-disk input[name=path]').val(res.path)
            unblockPage()
            $('#modal-edit-disk').modal('toggle');
        },
        error: function(err) {
            unblockPage()
            $('#modal-edit-disk').modal('toggle');
            console.log(err)
        },
        dataType: 'JSON'
    });

});

$('#form-edit-disk').on('submit', function(e) {

    $("#form-edit-disk input[name=password]").rules().laravelValidation.splice(0, 1);

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
            data: new FormData($('#form-edit-disk')[0]), // The form with the file inputs.
            processData: false,
            contentType: false // Using FormData, no need to process data.
        }).done(function(res) {
            successAjax('modal-edit-disk', 'updated', res) // (close modal by modal id, message, response)
        }).fail(function(err) {
            errorAjax(err, 'updated')
        });

    }
    // prevent bubbling event
    e.preventDefault();
    e.stopImmediatePropagation();
});

</script>