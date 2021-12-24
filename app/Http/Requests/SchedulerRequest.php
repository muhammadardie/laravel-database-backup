<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SchedulerRequest extends FormRequest
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
        $schedulerId = ($this->scheduler) ? $this->scheduler : 0;
        $returns = [
            'name'               => ['required', 'max:150', 'unique:scheduler,name,'.$schedulerId.',id'],
            'database_source_id' => ['required', 'numeric'],
            'storage_id'         => ['required', 'numeric'],
            'database'           => ['required'],
            'running'            => ['required'],
            'remark'             => ['max:150'],
            
        ];

        return $returns;
    }

}
