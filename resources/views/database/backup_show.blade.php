<div class="modal fade" id="modal-detail-backup" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-backup-title">Detail Backup</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-detail-backup" role="form">
          <div class="row">
            @include('partials.form-input', [
              'title'       => __('Disk'),
              'type'        => 'text',
              'name'        => 'disk',
              'multiColumn' => true,
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'       => __('Source'),
              'type'        => 'text',
              'name'        => 'source',
              'multiColumn' => true,
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'       => __('Database'),
              'type'        => 'text',
              'name'        => 'database',
              'multiColumn' => true,
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'       => __('Output'),
              'type'        => 'text',
              'name'        => 'name',
              'multiColumn' => true,
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'       => __('Path'),
              'type'        => 'text',
              'name'        => 'path',
              'multiColumn' => true,
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'       => __('Size'),
              'type'        => 'text',
              'name'        => 'size',
              'multiColumn' => true,
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'       => __('Created At'),
              'type'        => 'text',
              'name'        => 'created_at',
              'attribute'   => ['disabled'],
              'multiColumn' => true
            ])
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
          console.log(res)
          $('#form-detail-backup input[name=disk]').val(res.disk)
          $('#form-detail-backup input[name=source]').val(res.source)
          $('#form-detail-backup input[name=database]').val(res.database)
          $('#form-detail-backup input[name=name]').val(res.name)
          $('#form-detail-backup input[name=database]').val(res.database)
          $('#form-detail-backup input[name=path]').val(res.path)
          $('#form-detail-backup input[name=path]').attr('title', res.path);
          $('#form-detail-backup input[name=size]').val(res.size)
          $('#form-detail-backup input[name=created_at]').val(tglIndo(res.created_at))
          $('#modal-detail-backup').modal('toggle');
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