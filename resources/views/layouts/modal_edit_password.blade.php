<div class="modal fade" id="modal-edit-password" tabindex="-1" role="dialog" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title">Edit Password</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <form id="form-edit-password" role="form" action="{{ route('user.store') }}" method="post">
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="modal-body">
               <div class="row">
                  <div class="col-md-12">
                     <div class="col-md-12">
                        <div class="form-group row">
                           <label for="password" class="single-label" style="flex: 0 0 30%;max-width: 30%;">
                           <span style="color:red">*</span>
                           Password
                           </label>
                           <input type="password" class="form-control single-input" name="password" value="" placeholder="Password" autocomplete="on">
                        </div>
                        <div class="form-group row">
                           <label for="password_confirmation" class="single-label" style="flex: 0 0 30%;max-width: 30%;">
                           <span style="color:red">*</span>
                           Password Confirmation
                           </label>
                           <input type="password" class="form-control single-input" name="password_confirmation" value="" placeholder="Password Confirmation" autocomplete="on">
                        </div>
                     </div>
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
{!! JsValidator::formRequest('App\Http\Requests\EditPasswordRequest', '#form-edit-password') !!}
<script>
   $('body').on('click', '.edit-password-account', function(e) {
     e.preventDefault();
     $('#modal-edit-password').modal('toggle');
   });
   
   $('#form-edit-password').on('submit', function(e) {
       if ($(this).valid()) {
           blockPage()
   
           $.ajax({
               type: "POST",
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               url: '{{ route("user.changePassword", \Auth::user()->id) }}',
               cache: false,
               processData: false,
               data: new FormData($('#form-edit-password')[0]), // The form with the file inputs.
               processData: false,
               contentType: false // Using FormData, no need to process data.
           }).done(function(res) {
               successAjax('modal-edit-password', 'updated', res) // (close modal by modal id, message, response)
               document.getElementById('logout-form').submit();
           }).fail(function(err) {
               errorAjax(err, 'updated')
           });
   
       }
       // prevent bubbling event
       e.preventDefault();
       e.stopImmediatePropagation();
   });
   
</script>