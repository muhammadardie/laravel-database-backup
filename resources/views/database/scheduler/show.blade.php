<div class="modal fade" id="modal-detail-schedule" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-schedule-title">Detail Schedule</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-detail-schedule" role="form">
          <div class="row">
            <div class="col-md-12">
            @include('partials.form-input', [
              'title'       => __('Schedule Name'),
              'type'        => 'text',
              'name'        => 'name',
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'       => __('Storage'),
              'type'        => 'text',
              'name'        => 'storage',
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'       => __('Database Source'),
              'type'        => 'text',
              'name'        => 'database_source_id',
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'       => __('Database'),
              'type'        => 'text',
              'name'        => 'database',
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'     => __('Prune Days'),
              'type'        => 'text',
              'name'      => 'auto_prune_day',
              'attribute' => ['disabled']
            ])
            @include('partials.form-input', [
              'title'     => __('Status'),
              'type'        => 'text',
              'name'      => 'running',
              'attribute' => ['disabled']
            ])
            @include('partials.form-textarea', [
              'title'     => __('Remark'),
              'name'      => 'remark',
              'attribute' => ['disabled']
            ])
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
    // toggle modal and set content
    $('body').on('click', '.btn-show-datatable' , function(e) {
      e.preventDefault();
      blockPage()
    
      let url    = $(this).attr('data-href');

      // ajax for get modal content
      $.ajax({
        url: url,
        dataType: 'JSON',
        success: function(res) {
          $('#form-detail-schedule input[name=name]').val(res.name)
          $('#form-detail-schedule input[name=storage]').val(res.storageName)
          $('#form-detail-schedule input[name=database_source_id]').val(res.sourceName)
          $('#form-detail-schedule input[name=database]').val(res.listDatabase)
          $('#form-detail-schedule input[name=running]').val(res.status)
          $('#form-detail-schedule input[name=auto_prune_day]').val(res.auto_prune_day + " day")
          $('#form-detail-schedule textarea[name=remark]').val(res.remark)
          $('#modal-detail-schedule').modal('toggle');
        },
        error: function(err) {
          errorAjax(err, 'retrieved')
        },
        complete: function() {
          unblockPage()
        }
      });

    });


  });
</script>
</script>