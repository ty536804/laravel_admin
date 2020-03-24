<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $addRules=[
            'dp_name' =>'required|unique:sys_admin_department,dp_name',
        ];
        return $addRules;
    }
    
    public function messages()
    {
        return [
            'dp_name.required'=>'部门名称不能为空',
            'dp_name.unique'=>'部门名称不能重复',
        ];
    }
}
