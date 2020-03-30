<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class AdminPositionRequest extends FormRequest
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
        $id=Input::get('id');
        // Log::error('======inputs====',json_encode(Input::all()));
        $addRules=[
            'position_name' =>'required|unique:magic_math.sys_admin_position,position_name',
            'department_id' =>'required',
            'desc' =>'required',
        ];
        $updateRules=[
            'position_name' =>'required|unique:magic_math.sys_admin_position,position_name,'.$id.',id',
            'department_id' =>'required',
            'desc' =>'required',
        ];
        if ($id > 0){
            return $updateRules;
        }else{
            return $addRules;
        }
        
    }
    
    public function messages()
    {
        return [
            'position_name.required'=>'职位名称不能为空',
            'position_name.unique'=>'职位名称不能重复',
            'department_id.required'=>'归属部门不能为空',
            'desc.required'=>'职位描述不能为空',
        ];
    }
}
