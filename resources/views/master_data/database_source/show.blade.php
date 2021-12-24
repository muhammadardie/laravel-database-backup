<div class="modal fade" id="modal-detail-source" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-source-title">Detail Source</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-detail-source" role="form">
          <div class="row">
              @include('partials.form-input', [
              'title'       => __('Name'),
              'type'        => 'text',
              'name'        => 'name',
              'multiColumn' => true,
              'attribute'   => ['disabled']
            ])
            @include('partials.form-input', [
              'title'       => __('Host'),
              'type'        => 'text',
              'name'        => 'host',
              'attribute'   => ['disabled'],
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Username'),
              'type'        => 'text',
              'name'        => 'username',
              'attribute'   => ['disabled'],
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Port'),
              'type'        => 'number',
              'name'        => 'port',
              'attribute'   => ['disabled'],
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Type'),
              'type'        => 'text',
              'name'        => 'type',
              'attribute'   => ['disabled'],
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Created At'),
              'type'        => 'text',
              'name'        => 'created_at',
              'attribute'   => ['disabled'],
              'multiColumn' => true
            ])
            @include('partials.form-input', [
              'title'       => __('Updated At'),
              'type'        => 'text',
              'name'        => 'updated_at',
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
        success: function(res) {
          $('#form-detail-source input[name=name]').val(res.name)
          $('#form-detail-source input[name=host]').val(res.host)
          $('#form-detail-source input[name=username]').val(res.username)
          $('#form-detail-source input[name=port]').val(res.port)
          $('#form-detail-source input[name=database]').val(res.database)
          $('#form-detail-source input[name=type]').val(res.type)
          $('#form-detail-source input[name=created_at]').val(tglIndo(res.created_at))
          $('#form-detail-source input[name=updated_at]').val(tglIndo(res.updated_at))
          unblockPage()
          $('#modal-detail-source').modal('toggle');
        },
        error: function(err) {
          console.log(err)
        },
        dataType: 'JSON'
      });

    });


  });
</script>
</script>