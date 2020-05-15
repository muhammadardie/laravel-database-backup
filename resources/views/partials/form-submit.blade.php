<div class="card-action">
  <button class="btn btn-outline-success">Submit</button>
  <a class="btn btn-outline-danger ml-2 btn-back text-danger">Back</a>
</div>
<script>
$(document).ready(function() { 
	// on click submit block page and process
    $('form.form-submit').on('submit', function() {
		$(this).valid() && blockPage();
	}); 

    // on click back button redirect to index menu page
    $('a.btn-back').on('click', backToIndex);

    // on click enter auto submit
    $(document).keypress(function (e) {
	  if (e.which == 13) {
	    $('form.form-submit').submit();
	    return false;
	  }
	});

    function blockPage(){
		$.blockUI({ css: { 
	        border: 'none', 
	        padding: '15px', 
	        backgroundColor: '#000', 
	        '-webkit-border-radius': '10px', 
	        '-moz-border-radius': '10px', 
	        opacity: .5, 
	        color: '#fff' 
	    }}); 
	}

	function backToIndex(e){
		e.preventDefault();
		let redirect    = $('li.active').children().attr('href');
		window.location = redirect;
	}

}); 
</script>