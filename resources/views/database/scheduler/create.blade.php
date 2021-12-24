<div class="modal fade" id="modal-create-scheduler" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-backup-title">Create new scheduler backup</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-create-scheduler" role="form" action="{{ route('scheduler.store') }}" method="post">
      @csrf
      <div class="modal-body">
        <div class="row">
          <div class="col-md-12">
            @include('partials.form-input', [
              'title'       => __('Schedule Name'),
              'type'        => 'text',
              'name'        => 'name',
              'placeholder' => true,
              'required'    => true,
            ])
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
              'name'        => 'database[]',
              'data'        => [],
              'required'    => true,
              'multiple'    => true
            ])
            @include('partials.form-select', [
              'title'    => __('Status'),
              'name'     => 'running',
              'data'     => $statusScheduler,
              'required' => true
            ])
            @include('partials.form-textarea', [
              'title'    => __('Remark'),
              'name'     => 'remark'
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
{!! JsValidator::formRequest('App\Http\Requests\SchedulerRequest', '#form-create-scheduler') !!}
<script>

  $("select[name=database\\[\\]]").select2({ 
    placeholder: "-- Select Database --", 
    width: "50%"
  });

  $('#form-create-scheduler').on('submit', function(e) {
        e.preventDefault();
        
        if( $(this).valid() ){
            blockPage()
          
            $.ajax({
               type: "POST",
               headers: {
                  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url:'{{ route('scheduler.store') }}',
               cache: false,
               processData: false,
               data: new FormData($('#form-create-scheduler')[0]), // The form with the file inputs.
               processData: false,
               contentType: false                    // Using FormData, no need to process data.
            }).done(function(res){

              if(res.status === true)
              {
                successAjax('modal-create-scheduler', 'saved', res)
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
</script>