<div class="form-group {{ isset($formClass) ? $formClass : '' }}">
    <label for="Status">
      <span style="color:red" title="Wajib diisi">*</span>
      Status
    </label>
    <select name="aktif" class="form-control {{ $errors->has('aktif') ? ' is-invalid' : '' }}">
        <option></option>
        <option value="1" @if(isset($selected) && $selected === true) selected @endif>Aktif</option>
        <option value="0" @if(isset($selected) && $selected === false) selected @endif>Tidak Aktif</option>
  </select>
  @if ($errors->has('aktif'))
      <div class="invalid-feedback">
          {{ $errors->first('aktif') }}
      </div>
  @endif
</div>
<script>
  $("select[name='aktif']").select2({ placeholder: "-- Pilih Status--", width: '100%' });  
</script>