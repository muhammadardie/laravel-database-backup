<div class="modal fade" id="modal-create-backup" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-backup-title">Create new manual backup</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-create-backup" role="form" action="{{ route('histories.create') }}" method="post">
      @csrf
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            @include('partials.form-select', [
              'title'       => __('Storage'),
              'name'        => 'storage_id',
              'data'        => $storage,
              'required'    => true,
            ])
            @include('partials.form-select', [
              'title'       => __('Database Source'),
              'name'        => 'database_source_id',
              'data'        => $dbSources,
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
              'name'        => 'filename',
              'placeholder' => true,
              'required'    => true,
            ])

            <input type="hidden" name="backup_type" value="manual" />
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
{!! JsValidator::formRequest('App\Http\Requests\ManualBackupRequest', '#form-create-backup') !!}
<script>

  $('body').on('change', 'select[name=database_source_id]', function() {
    blockPage()
    $.ajax({
        url: '{{ route('histories.getDatabaseList') }}', //this is your uri
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
    $('input[name=filename]').val(defaultName( this.value ))
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
               url:'{{ route('histories.store') }}',
               cache: false,
               processData: false,
               data: new FormData($('#form-create-backup')[0]), // The form with the file inputs.
               processData: false,
               contentType: false                    // Using FormData, no need to process data.
            }).done(function(res){
              if(res.status === true)
              {
                successAjax('modal-create-backup', 'saved', res)
              }
              else
              {
                failedAjax(res, res.msg)
              }

              return;
            }).fail(function(err){
               errorAjax(err, err.msg)
            });
        }
  });

  function defaultName(databaseName) {
    let now = @json(date('d-m-Y--H-i-s'));
    
    return `${databaseName}--${now}.backup`;
  }
</script>