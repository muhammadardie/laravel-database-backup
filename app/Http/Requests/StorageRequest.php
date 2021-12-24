<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorageRequest extends FormRequest
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
        $storage_id = ($this->storage) ? $this->storage : 0;
        $returns = [
            'name'     => ['required', 'max:150'],
            'host'     => ['required', 'max:150', 'unique:storage,host,'.$storage_id.',id'],
            'username' => ['required', 'max:150'],  
            'password' => ['required', 'max:150'],
            'port'     => ['required', 'numeric'],
            'path'     => ['required', 'max:150'], 
        ];

        if($storage_id != 0){
            $returns['password'] = ['nullable'];
        }

        return $returns;
    }

}
