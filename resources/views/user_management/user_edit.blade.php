<div class="modal fade" id="modal-edit-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-user-title">Edit user</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-edit-user" role="form" action="{{ route('user.store') }}" method="post">
      <input type="hidden" name="_method" value="PUT">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
{!! JsValidator::formRequest('App\Http\Requests\UserRequest', '#form-edit-user') !!}
<script>
// get data from action button in datatable then pass into modal
$('body').on('click', '.btn-edit-datatable', function(e) {
    e.preventDefault();
    blockPage()

    let url = $(this).attr('data-href');
    // set form action for submit
    $('#form-edit-user').attr('action', url)

    // ajax for get modal content
    $.ajax({
        url: url,
        success: function(res) {
            let pathImage = '{{ asset("uploaded_files/user") }}' + '/' + res.photo;
            // destroy and re-initialize fileinput with initialpreview image
            $('#form-edit-user input[name=photo]')
              .fileinput('destroy')
              .fileinput({
                  showConsoleLogs: false,
                  showUpload: false,
                  showBrowse: false,
                  fileActionSettings: {
                    showZoom: false
                  },
                  browseOnZoneClick: true,
                  theme: 'fas',
                  overwriteInitial: true,
                  maxFileSize: 2000,
                  showClose: false,
                  showCaption: false,
                  elErrorContainer: '.kv-avatar-errors',
                  msgErrorClass: 'alert alert-block alert-danger',
                  allowedFileTypes: ['image'],   // allow only images
                  initialPreview: `<img src='${pathImage}' class='file-preview-image img-fluid'>`
              });
            $('#form-edit-user input[name=name]').val(res.name)
            $('#form-edit-user input[name=email]').val(res.email)
            $('#form-edit-user select[name=role]').val(res.role).trigger('change')
            unblockPage()
            $('#modal-edit-user').modal('toggle');
        },
        error: function(err) {
            unblockPage()
            $('#modal-edit-user').modal('toggle');
            console.log(err)
        },
        dataType: 'JSON'
    });

});

$('#form-edit-user').on('submit', function(e) {

    $("#form-edit-user input[name=password]").rules().laravelValidation.splice(0, 1);

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
            data: new FormData($('#form-edit-user')[0]), // The form with the file inputs.
            processData: false,
            contentType: false // Using FormData, no need to process data.
        }).done(function(res) {
            successAjax('modal-edit-user', 'updated', res) // (close modal by modal id, message, response)
        }).fail(function(err) {
            errorAjax(err, 'updated')
        });

    }
    // prevent bubbling event
    e.preventDefault();
    e.stopImmediatePropagation();
});

</script>