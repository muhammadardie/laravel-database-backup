<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $user_id = ($this->user) ? $this->user : 0;
        $returns = [
            'email'        => ['required','email', 'max:150', 'unique:user,email,'.$user_id.',user_id'],
            'karyawan_id'  => ['required', 'integer'],
            'password'     => ['required','string','min:6','confirmed'],
        ];
        
        if($user_id != 0){
            $returns['password'] = ['nullable','string','min:6','confirmed'];
        }

        return $returns;
    }

}
