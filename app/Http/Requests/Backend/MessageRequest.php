<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
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
        return [
            //
            'mname' => 'required',
            'area' => 'required',
            'tel' => 'required'
        ];
    }
    
    public function messages()
    {
        return [
            'mname.required' => '姓名不能为空',
            'area.required' => '地区不能为空',
            'tel.required' => '电话不能为空'
        ];
    }
}
