<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SiteRequest extends FormRequest
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
            "site_title" => "required",
            "site_desc" => "required",
            "site_keyboard" => "required",
            "site_copyright" => "required",
            "site_tel" => "required",
            "site_email" => "required",
            "site_address" => "required",
        ];
    }
    
    public function messages()
    {
        return [
            "site_title.required" => "网站标题不能为空",
            "site_desc.required" => "网站描述不能为空",
            "site_keyboard.required" => "网站关键字不能为空",
            "site_copyright.required" => "网站版权不能为空",
            "site_tel.required" => "联系电话不能为空",
            "site_email.required" => "联系邮箱不能为空",
            "site_address.required" => "联系地址不能为空",
        ];
    }
}
