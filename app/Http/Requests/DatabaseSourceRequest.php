<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatabaseSourceRequest extends FormRequest
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
        $databaseSourceId = ($this->database_source) ? $this->database_source : 0;
        $returns = [
            'name'     => ['required', 'max:150'],
            'type'     => ['required'],
            'host'     => ['required', 'max:150', 'unique:database_sources,host,'.$databaseSourceId.',id'],
            'username' => ['required', 'max:150'],  
            'password' => ['required', 'max:150'],
            'port'     => ['required', 'numeric']
        ];

        if($databaseSourceId != 0){
            $returns['password'] = ['nullable'];
        }

        return $returns;
    }

}
