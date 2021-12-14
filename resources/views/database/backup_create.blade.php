<div class="modal fade" id="modal-create-backup" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-backup-title">Create new backup</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-create-backup" role="form" action="{{ route('backup.createBackup') }}" method="post">
      @csrf
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            @include('partials.form-select', [
              'title'       => __('Disk'),
              'name'        => 'disk_id',
              'data'        => $disks,
              'required'    => true,
            ])
            @include('partials.form-select', [
              'title'       => __('Source'),
              'name'        => 'source_id',
              'data'        => $sources,
              'required'    => true,
            ])
            @include('partials.form-select', [
              'title'       => __('Database'),
              'name'        => 'database',
              'data'        => [],
              'required'    => true
            ])
            @include('partials.form-input', [
              'title'       => __('Output'),
              'type'        => 'text',
              'name'        => 'name',
              'placeholder' => true,
              'required'    => true,
            ])
            @include('partials.form-input', [
              'title'       => __('Path'),
              'type'        => 'text',
              'name'        => 'path',
              'placeholder' => true
            ])
          </div>
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
{!! JsValidator::formRequest('App\Http\Requests\BackupRequest', '#form-create-backup') !!}
<script>

  $('body').on('change', 'select[name=source_id]', function() {
    blockPage()
    $.ajax({
        url: '{{ route('backup.getDatabaseList') }}', //this is your uri
        type: 'GET', //this is your method
        data: { id: this.value },
        dataType: 'json',
        success: function(res){
          unblockPage()
          if(!jQuery.isEmptyObject(res)){
          
            listOpt = "<option value=''></option>"; // reserved for placeholder
            $.each(res, function(i,v){
              listOpt += "<option value='"+ i +"'>"+ v +"</option>";
            });
            
            $("select[name=database]").html(listOpt);
          } else {
            errorAjax(res, 'retrieve')
          }
            
        },
        error: function(err) {
          unblockPage()
          errorAjax(err, 'retrieve')
        }
    });
  })

  $('body').on('change', 'select[name=database]', function() {
    $('input[name=name]').val(defaultName( this.value ))
  })

  $('#form-create-backup').on('submit', function(e) {
        e.preventDefault();
        
        if( $(this).valid() ){
            blockPage()
          
            $.ajax({
               type: "POST",
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url:'{{ route('backup.createBackup') }}',
               cache: false,
               processData: false,
               data: new FormData($('#form-create-backup')[0]), // The form with the file inputs.
               processData: false,
               contentType: false                    // Using FormData, no need to process data.
            }).done(function(res){
              if(typeof res.status === 'string') {
                unblockPage()
                var notification = alertify.message(
                  `<span class="fas fas fa-times"> </span> &nbsp; ${res.status}`,
                  5,
                );

                return;
              }

               successAjax('modal-create-backup', 'saved', res) // (close modal by modal id, message, response)
      
            }).fail(function(err){
               errorAjax(err, err)
            });
        }
  });

  function defaultName(databaseName) {
    let now = @json(date('d-m-Y--H-i-s'));
    
    return `${databaseName}--${now}.backup`;
  }
</script>