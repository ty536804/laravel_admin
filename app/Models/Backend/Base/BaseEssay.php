<?php
namespace App\Models\Backend\Base;

use Illuminate\Database\Eloquent\Model;

class BaseEssay extends Model
{
    
    protected $table='essay';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'banner_position_id', // 位置
        'essay_title', // 标题
        'essay_content', // 内容
        'essay_img', // 缩率图
        'essay_status', // 状态 1显示 0隐藏
        'essay_img_info', // 缩率图信息
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-03-31 02:35:56