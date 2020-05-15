<div class="form-group row">
    <label for="{{ $name }}" class="col-form-label">
        @if( isset($required) && $required === true )
            <span style="color:red">*</span>
        @endif
        {{ $title }}
    </label>

    <input  
    type="{{ isset($type) ? $type : 'text' }}" 
    class="form-control col-form"
    name="{{ $name }}"
    value="{{ isset($value) ? $value : '' }}"
    placeholder="{{ isset($placeholder) && $placeholder === true ? $title : $placeholder }}" 
    {{ (isset($attribute) ? is_array($attribute) : false) ? implode(' ',$attribute) : ''}}>
</div>

@if(isset($date) && $date === true)
    <script>
        let input = $('input[name={{ $name }}]'); 
        <?= \Helper::date_formats('$(input)', 'js') ?>
    </script>
@endif