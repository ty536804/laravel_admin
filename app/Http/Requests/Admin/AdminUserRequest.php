<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Input;

class AdminUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        $id = Input::get('id');
        $sid = Input::get('sid');
        $addRules=[
            'nick_name' =>'required',
            'login_name' =>'required|unique:magic_math.sys_admin_user,login_name|regex:/^[0-9a-zA-Z]+$/',
            'email' =>'required|unique:magic_math.sys_admin_user,email|regex:/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',
            'tel' =>'required|unique:magic_math.sys_admin_user,tel|regex:/^1[34578][0-9]\d{4,8}$/',
            'pwd' =>'required|between:5,15',
            'city_id'=>'required',
        ];
        $updateRules=[
            'nick_name' =>'required',
            'email' =>'required|unique:magic_math.sys_admin_user,email,'.$id.',id|regex:/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',
            'tel' =>'required|unique:magic_math.sys_admin_user,tel,'.$id.',id|regex:/^1[34578][0-9]\d{4,8}$/',
            //    'pwd' =>'required|regex:/^[0-9a-zA-Z_]+$/|between:5,15',
            'city_id'=>'required',
        ];
        $setRules=[
            'nick_name' =>'required',
            'email' =>'required|unique:magic_math.sys_admin_user,email,'.$id.',id|regex:/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/',
            'tel' =>'required|unique:magic_math.sys_admin_user,tel,'.$id.',id|regex:/^1[34578][0-9]\d{4,8}$/',
        ];
        if ($sid == 5){
            return $setRules;
        }
        if ($id > 0){
            return $updateRules;
        }else{
            return $addRules;
        }
        
    }
    
    public function messages()
    {
        return [
            'nick_name.required'=>'用户名称不能为空',
            'login_name.required'=>'用户账号不能为空',
            'login_name.unique'=>'用户账号不能重复',
            'login_name.regex'=>'用户账号应为-小写,大写,数字',
            'email.required'=>'邮箱名称不能为空',
            'email.unique'=>'邮箱名称不能重复',
            'email.regex'=>'邮箱格式不正确',
            'pwd.required'=>'密码不能为空',
            'pwd.regex'=>'密码格式不正确',
            'pwd.between'=>'密码在5-15位之间',
            'tel.required'=>'手机号不能为空',
            'tel.unique'=>'手机号不能重复',
            'tel.regex'=>'手机号格式不正确',
//            'city_id.required'=>' 必须选择城市',
        ];
    }
}
