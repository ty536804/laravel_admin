<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminPowerRequest extends FormRequest
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
        
        $id=input ::get('id');
        $addRules=[
            'pname' =>'required',
            'icon' =>'required',
            'pindex' =>'required',
            'purl' =>'required',
        ];
        
        $updateRules=[
            'pname' =>'required',
            'icon' =>'required',
            'purl' =>'required',
            'pindex' =>'required',
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
            'pname.required'=>'权限名称不能为空',
            'icon.required'=>'权限图标不能为空',
            'pindex.required'=>'排序不能为空',
            'purl.required'=>'权限链接不能为空',
        ];
    }
}
