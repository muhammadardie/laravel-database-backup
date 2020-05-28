<div class="form-group {{ isset($multiColumn) && $multiColumn ? 'col-md-6' : 'col-md-12' }}">
    <label for="{{ $name }}" class="{{ isset($multiColumn) && $multiColumn ? 'col-md-6' : 'col-md-12' }}">
        {{ $title }}
    </label>
    <div class="kv-avatar col-form">
      <div class="kv-avatar-errors col-md-12" style="display:none"></div> 
        <div class="file-loading">
            <input name="{{ $name }}" type="file" class="form-control {{ isset($multiColumn) && $multiColumn ? 'col-md-6' : 'col-md-12' }}" {{ isset($disabled) && $disabled ? 'disabled' : ''}}>
        </div>
        @if(!isset($disabled))
            <span class="text-danger">Allowed type:jpg, jpeg, png &emsp; Max Size:2MB</span>
        @endif
    </div>
</div>
<script>
$("input[name={{ $name }}]").fileinput({
    showConsoleLogs: false,
    showUpload: false,
    showBrowse: false,
    fileActionSettings: {
      showZoom: false
    },
    browseOnZoneClick: true,
    theme: 'fas',
    overwriteInitial: true,
    maxFileSize: 2000,
    showClose: false,
    showCaption: false,
    elErrorContainer: '.kv-avatar-errors',
    msgErrorClass: 'alert alert-block alert-danger',
    allowedFileTypes: ['image']   // allow only images
});
</script>