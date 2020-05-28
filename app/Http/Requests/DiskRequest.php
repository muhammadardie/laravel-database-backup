<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DiskRequest extends FormRequest
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
        $disk_id = ($this->disk) ? $this->disk : 0;
        $returns = [
            'name'     => ['required', 'max:150'],
            'host'     => ['required', 'max:150', 'unique:disks,host,'.$disk_id.',id'],
            'username' => ['required', 'max:150'],  
            'password' => ['required', 'max:150'],
            'port'     => ['required', 'numeric']
        ];

        if($disk_id != 0){
            $returns['password'] = ['nullable'];
        }

        return $returns;
    }

}
