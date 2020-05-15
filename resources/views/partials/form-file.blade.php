<div class="form-group row">
    <label for="{{ $name }}" class="col-form-label">
        @if( isset($required) && $required === true )
            <span style="color:red">*</span>
        @endif
        {{ $title }}
    </label>
    <div class="kv-avatar col-form">
      <div id="kv-avatar-errors" class="col-md-12" style="display:none"></div> 
        <div class="file-loading">
            <input id="{{ $name }}" name="{{ $name }}" type="file" class="form-control col-form">
        </div>
        <span class="text-danger">Allowed type:jpg, jpeg, png &emsp; Max Size:2MB</span>
    </div>
</div>
<script>
$("#{{ $name }}").fileinput({
    showUpload: false,
    showBrowse: false,
    fileActionSettings: {
      showZoom: false
    },
    browseOnZoneClick: true,
    required: true,
    theme: 'fas',
    overwriteInitial: true,
    maxFileSize: 2000,
    showClose: false,
    showCaption: false,
    elErrorContainer: '#kv-avatar-errors',
    msgErrorClass: 'alert alert-block alert-danger',
    allowedFileTypes: ['image']   // allow only images
});
</script>