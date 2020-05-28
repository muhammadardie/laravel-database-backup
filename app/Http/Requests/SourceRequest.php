<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SourceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $source_id = ($this->source) ? $this->source : 0;
        $returns = [
            'name'     => ['required', 'max:150'],
            'type'     => ['required'],
            'host'     => ['required', 'max:150', 'unique:sources,host,'.$source_id.',id'],
            'username' => ['required', 'max:150'],  
            'password' => ['required', 'max:150'],
            'port'     => ['required', 'numeric']
        ];

        if($source_id != 0){
            $returns['password'] = ['nullable'];
        }

        return $returns;
    }

}
