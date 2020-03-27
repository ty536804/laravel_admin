<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'title' =>'required',
            'summary' => 'required',
            'thumb_img_info' => 'required',
            'admin' => 'required',
            'com' => 'required',
        ];
        
    }
    
    public function messages() {
        return [
            'title.required'=>'标题不能为空',
            'summary.required'=>'摘要不能为空',
            'thumb_img_info.required'=>'缩率图不能为空',
            'admin.required'=>'编辑人员不能为空',
            'com.required'=>'文章来源不能为空',
        ];
    }
}
