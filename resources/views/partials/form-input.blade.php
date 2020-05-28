<div class="form-group {{ isset($multiColumn) && $multiColumn ? 'col-md-6' : 'row' }}">
    <label for="{{ $name }}" class="{{ isset($multiColumn) && $multiColumn ? '' : 'single-label' }}">
        @if( isset($required) && $required === true )
            <span style="color:red">*</span>
        @endif
        {{ $title }}
    </label>

    <input  
    type="{{ isset($type) ? $type : 'text' }}" 
    class="form-control {{ isset($multiColumn) && $multiColumn ? 'col-md-12' : 'single-input' }}"
    name="{{ $name }}"
    value="{{ isset($value) ? $value : '' }}"
    placeholder="{{ isset($placeholder) && $placeholder === true ? $title : '' }}" 
    {{ (isset($attribute) ? is_array($attribute) : false) ? implode(' ',$attribute) : ''}}>
</div>

@if(isset($date) && $date === true)
    <script>
        let input = $('input[name={{ $name }}]'); 
        <?= \Helper::date_formats('$(input)', 'js') ?>
    </script>
@endif