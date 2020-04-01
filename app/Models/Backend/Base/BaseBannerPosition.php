<?php
namespace App\Models\Backend\Base;

use Illuminate\Database\Eloquent\Model;

class BaseBannerPosition extends Model
{
    
    protected $table='banner_position';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'position_name', // 位置名称
        'base_url', // 跳转地址
        'image_size', // 图片大小 长*高*宽
        'info', // 备注
        'is_show', // 状态 1显示 2隐藏
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-04-01 07:50:29