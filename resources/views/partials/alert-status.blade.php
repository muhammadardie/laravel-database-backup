@if(Session::has('alert'))
	<div class="alert {{ Session::get('alert') }} alert-dismissible fade show" role="alert">
	  <strong>{{ Session::get('alert-text') }}</strong>
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
	    <span aria-hidden="true">&times;</span>
	  </button>
	</div>
@endif