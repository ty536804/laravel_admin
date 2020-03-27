<?php
namespace App\Models\Backend\Base;

use Illuminate\Database\Eloquent\Model;

class BaseArticle extends Model
{
    
    protected $table='article';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'title', // 标题
        'summary', // 摘要
        'thumb_img', // 缩率图
        'admin', // 编辑者
        'com', // 来源
        'is_show', // 是否展示 1展示 2不展示
        'content', // 内容
        'hot', // 是否热点 1是 2否
        'sort', // 优先级 数字越大，排名越前
        'thumb_img_info', // 缩率图相关信息
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-03-27 02:18:57