<div class="form-group {{ isset($formClass) ? $formClass : '' }}">
    <label for="{{ $name }}">
      {!! isset($required) && $required === true ? '<span style="color:red" title="Wajib diisi">*</span> ' : '' !!}
      {{ $title }}
    </label>
    <select name="{{ $name }}" class="form-control {{ $errors->has($name) ? ' is-invalid' : '' }}" data-changed="{{ isset($nextSel) ? $nextSel : '' }}"
    data-url="{{ isset($nextUrl) ? $nextUrl : '' }}"
    {{ isset($multiple) && $multiple === true ? 'multiple="multiple"' : '' }}
    {{ (isset($attribute) ? is_array($attribute) : false) ? implode(' ',$attribute) : ''}}>
        <option></option>
        @if(isset($optgroup))
          @foreach($data as $value)
            <optgroup label="{{ $value->nama }}">
              @foreach($value->$optgroup as $valueChild)
                <option value="{{ $valueChild->getKey() }}" 
                  {{ (isset($selected) && is_array($selected) && in_array($valueChild->getKey(), $selected)) ? 'selected' : ''}}
                >{{ $valueChild->nama }}</option>
              @endforeach
            </optgroup>
          @endforeach
        @else
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
        @endif
  </select>
  @if ($errors->has($name))
      <div class="invalid-feedback">
          {{ $errors->first($name) }}
      </div>
  @endif
  <span id="loading-{{ $name }}" style="display:none"><img src="{{ asset('images/loading.gif') }}"></span>
</div>
<script>
  $("select[name='{{ $name }}']").select2({ placeholder: "{{ \Lang::get('select2.select_'.$name) }}", width: '100%' });
  
  @if(isset($nextSel))
    $('body').on('change', "select[name='{{ $name }}']", function(){
        let thisVal = $(this).val().trim();
        let nextSel = $(this).attr('data-changed');
        let loading = $('#loading-{{ $name }}');
        let url     = $(this).attr('data-url');

        loading.css({'display': 'block'});
        
        // reset
        $("select[name="+nextSel+"]").html('');
                  
        $.ajax({
            method: 'GET',
            url: url,
            data: {id: thisVal},
            success: function(msg){
                loading.css({'display': 'none'});

                listOpt = "<option value=''></option>";
                if(!jQuery.isEmptyObject(msg)){
                    $.each(msg, function(i,v){
                        listOpt += "<option value='"+ v._id +"'>"+ v.nama +"</option>";
                    });
                }else{
                    listOpt = "<option></option>";
                }

                
                $("select[name="+nextSel+"]").html(listOpt);
            },
            error: function(err){
                alert(JSON.stringify(err));
                loading.css({'display': 'none'});
            }
        });
    }); 
  @endif
  
</script>