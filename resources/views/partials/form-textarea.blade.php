@if( isset($summernote) && $summernote === true)
	<style>
	/*editor*/
	.note-editing-area {
	    z-index: -1 !important;
	}

	/*dropdown*/
	.note-check {
	    z-index: auto !important;
	}
	</style>
@endif

<div class="form-group {{ isset($formClass) ? $formClass : '' }}">
	<label for="{{ $name }}">
		{!! isset($required) && $required === true ? '<span style="color:red" title="Wajib diisi">*</span> ' : '' !!}
		{{ $title }}
	</label>
	@if(isset($labelIcon) && $labelIcon === true)
        <img class="label-flag" src="{{ asset('images/indonesia.svg')}}" alt="indonesia">
    @endif
	<textarea class="form-control {{ isset($inputClass) ? $inputClass : '' }}"
		name="{{ $name }}"
		{{ (isset($attribute) ? is_array($attribute) : false) ? implode(' ',$attribute) : ''}}
		rows="3" required="required">{{ old($name, isset($value) ? $value : '') }}</textarea>
</div>

@if( isset($summernote) && $summernote === true)
	<script type="text/javascript">
	$(document).ready(function() {
	  $('textarea[name={{ $name }}]').summernote({
	  	  toolbar: [
	        // [groupName, [list of button]]
	        ['style', ['bold', 'italic', 'underline', 'clear']],
	        ['font', ['strikethrough', 'superscript', 'subscript']],
	        ['fontsize', ['fontsize']],
	        ['color', ['color']],
	        ['para', ['ul', 'ol', 'paragraph']],
	        ['height', ['height']],
	        ['view', ['fullscreen', 'codeview']],
	        ['fontname', ['fontname']],
	      ],
	      height: 300,                 // set editor height
	      minHeight: null,             // set minimum height of editor
	      maxHeight: null,   
	  });

	  @if( (isset($attribute) && implode(' ',$attribute) == 'disabled') )
	    $('textarea[name={{ $name }}]').summernote('disable');
	  @endif

	});

	</script>
@endif