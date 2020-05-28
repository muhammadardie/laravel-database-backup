<div class="card-action">
  <button class="btn btn-outline-success">Submit</button>
  <a class="btn btn-outline-danger ml-2 btn-back text-danger">Back</a>
</div>
<script>
$(document).ready(function() { 
    // on click enter auto submit
    $(document).keypress(function (e) {
	  if (e.which == 13) {
	    $('form.form-submit').submit();
	    return false;
	  }
	});
}); 
</script>