<div class="modal fade" id="modal-detail-user" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal-show-user-title">Detail User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="form-detail-user" role="form">
          <div class="row">
              <div class="form-group col-md-12">
                <center>
                  <image id="detail-photo-user" class="rounded-circle" src="" height="100px" />
                </center>
              </div>
              @include('partials.form-input', [
                'title'       => __('Name'),
                'type'        => 'text',
                'name'        => 'name',
                'multiColumn' => true,
                'attribute'   => ['disabled']
              ])
              @include('partials.form-input', [
                'title'       => __('Email'),
                'type'        => 'text',
                'name'        => 'email',
                'multiColumn' => true,
                'attribute' => ['disabled']
              ])
              @include('partials.form-input', [
                'title'       => __('Role'),
                'type'        => 'text',
                'name'        => 'role',
                'multiColumn' => true,
                'attribute' => ['disabled']
              ])
              @include('partials.form-input', [
                'title'       => __('Last login'),
                'type'        => 'text',
                'name'        => 'last_login',
                'multiColumn' => true,
                'attribute' => ['disabled']
              ])
              @include('partials.form-input', [
                'title'       => __('Created At'),
                'type'        => 'text',
                'name'        => 'created_at',
                'multiColumn' => true,
                'attribute' => ['disabled']
              ])
              @include('partials.form-input', [
                'title'       => __('Updated At'),
                'type'        => 'text',
                'name'        => 'updated_at',
                'multiColumn' => true,
                'attribute' => ['disabled']
              ])
              <div class="form-group col-md-6">
                  <label>Status</label>
                <br />
                <span name="is_logged"></span>
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
        success: function(res) {
          let imagePath = res.photo ? '{{ asset("uploaded_files/user") }}' + '/' + res.photo :
                          '{{ asset('assets/img/avatar.png') }}'; // if not exist use default avatar
    
          $('#detail-photo-user').attr("src", imagePath);
          $('#form-detail-user input[name=name]').val(res.name)
          $('#form-detail-user input[name=email]').val(res.email)
          $('#form-detail-user input[name=role]').val(res.role)
          $('#form-detail-user span[name=is_logged]').html(onlineStatus(res.is_logged))
          $('#form-detail-user input[name=last_login]').val(tglIndo(res.last_login))
          $('#form-detail-user input[name=created_at]').val(tglIndo(res.created_at))
          $('#form-detail-user input[name=updated_at]').val(tglIndo(res.updated_at))
          unblockPage()
          $('#modal-detail-user').modal('toggle');
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