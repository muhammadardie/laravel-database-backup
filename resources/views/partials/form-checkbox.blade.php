<div class="form-group {{ isset($formClass) ? $formClass : '' }} d-flex align-items-center">
    <label for="{{ $name }}" class="form-check-label ml-4">
        {!! isset($required) && $required === true ? '<span style="color:red" title="Wajib diisi">*</span> ' : '' !!}
        
    @if(isset($inputGroup) && is_array($inputGroup))
        <div class="input-group">
        
        @if($inputGroup['index'] == 'prepend')
          <div class="input-group-prepend">
            <span class="input-group-text">{!! $inputGroup['text'] !!}</span>
          </div>
        @endif
        
    @endif
        <input 
        id="{{ $name }}" 
        type="{{ $type }}" 
        class="form-check-input {{ $errors->has($name) ? ' is-invalid' : '' }}"
        name="{{ $name }}"
        value="{{ old($name, isset($value) ? $value : '0') }}"
        placeholder="{{ isset($placeholder) ? $placeholder :  '' }}" 
        {{ (isset($attribute) ? is_array($attribute) : false) ? implode(' ',$attribute) : ''}}>
    {{ $title }}
    </label>
    @if(isset($inputGroup) && is_array($inputGroup) && $inputGroup['index'] == 'append')
      <div class="input-group-append">
        <span class="input-group-text">{{ $inputGroup['text'] }}</span>
      </div>
    @endif

        @if ($errors->has($name))
            <div class="invalid-feedback">
                {{ $errors->first($name) }}
            </div>
        @endif
        @if(isset($inputGroup) && is_array($inputGroup))
            </div>
        @endif
</div>