<div class="modal fade" id="modal-edit-schedule" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-schedule-title">Edit schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-edit-schedule" role="form" action="{{ route('scheduler.store') }}" method="post">
      <input type="hidden" name="_method" value="PUT">
      <input type="hidden" name="_token" value="{{ csrf_token() }}">
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
            @include('partials.form-select', [
              'title'       => __('Prune Days'),
              'name'        => 'auto_prune_day',
              'data'        => $availablePruneDays
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
{!! JsValidator::formRequest('App\Http\Requests\StorageRequest', '#form-edit-schedule') !!}
<script>

// get data from action button in datatable then pass into modal
$('body').on('click', '.btn-edit-datatable', function(e) {
    e.preventDefault();
    blockPage()

    let url = $(this).attr('data-href');
    // set form action for submit
    $('#form-edit-schedule').attr('action', url)

    // ajax for get modal content
    $.ajax({
        url: url,
        success: function(res) {
          const database = JSON.parse(res.database);
          const status = res.running ? '1' : '0';

          $('#form-edit-schedule input[name=name]').val(res.name)
          $('#form-edit-schedule select[name=storage_id]').val(res.storage_id).trigger('change');
          $('#form-edit-schedule select[name=database_source_id]').val(res.database_source_id).trigger('change.select2');

          // create list select option database
          listDb = "<option value=''></option>"; // reserved for placeholder
          $.each(res.availableDatabase, function(i,v){
            listDb += "<option value='"+ i +"'>"+ v +"</option>";
          });
          
          $("select[name=database\\[\\]]").html(listDb).select2({ 
            placeholder: "-- Select Database --", 
            width: "50%"
          });

          $('#form-edit-schedule select[name=database\\[\\]]').val(database).trigger('change');
          $('#form-edit-schedule select[name=running]').val(status).trigger('change');
          $('#form-edit-schedule select[name=auto_prune_day]').val(res.auto_prune_day).trigger('change');
          $('#form-edit-schedule textarea[name=remark]').val(res.remark)
          unblockPage()
          $('#modal-edit-schedule').modal('toggle');
        },
        error: function(err) {
            unblockPage()
            $('#modal-edit-schedule').modal('toggle');
            console.log(err)
        },
        dataType: 'JSON'
    });

});

$('#form-edit-schedule').on('submit', function(e) {

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
            data: new FormData($('#form-edit-schedule')[0]), // The form with the file inputs.
            processData: false,
            contentType: false // Using FormData, no need to process data.
        }).done(function(res) {
            if(res.status === true)
            {
              successAjax('modal-edit-schedule', 'updated', res)
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