<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManualBackupRequest extends FormRequest
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
        $returns = [
            'storage_id'         => ['required'],
            'database_source_id' => ['required'],
            'database'           => ['required'],
            'filename'           => ['required'],
        ];

        return $returns;
    }

}
