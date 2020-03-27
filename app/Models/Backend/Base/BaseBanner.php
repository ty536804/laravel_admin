<?php
namespace App\Models\Backend\Base;

use Illuminate\Database\Eloquent\Model;

class BaseBanner extends Model
{
    
    protected $table='banner';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'province', // 省
        'city', // 市
        'area', // 区
        'bname', // 名称
        'bposition', // 位置 1 首页
        'imgurl', // 图片地址
        'target_link', // 跳转链接
        'begin_time', // 显示开始时间
        'end_time', // 显示结束时间
        'is_show', // 状态 1显示 2隐藏
        'image_size', // 图片大小 长*高*宽
        'info', // 备注
        'img_info', // 图片详细
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-03-27 02:19:18