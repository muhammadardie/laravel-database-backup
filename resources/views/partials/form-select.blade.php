<div class="form-group {{ isset($multiColumn) && $multiColumn ? 'col-md-6' : 'row' }}">
    <label for="{{ $name }}" class="{{ isset($multiColumn) && $multiColumn ? '' : 'single-label' }}">
        @if( isset($required) && $required === true )
            <span style="color:red">*</span>
        @endif
        {{ $title }}
    </label>

    <select 
      name="{{ $name }}" 
      class="form-control {{ isset($multiColumn) && $multiColumn ? 'col-md-12' : 'single-input' }}"
      @if( isset($multiple) && $multiple === true ) 
        multiple="multiple"
      @endif
    >

      <option></option>
        @foreach($data as $key => $value)
            <option value="{{ $key }}" 
            @if(isset($selected) && !is_array($selected) && $selected== $key)
              selected
            @elseif(isset($selected) && is_array($selected) && in_array($key, $selected))
              selected
            @endif
            > 
              {{ $value }} 
            </option>
        @endforeach
    </select>
</div>

<script>
  $("select[name='{{ $name }}']").select2({ 
    placeholder: "{{ \Lang::get('-- Select '. $title . ' --') }}", 
    width: '{{ isset($multiColumn) && $multiColumn ? '100%' : '50%' }}'
  });
</script>