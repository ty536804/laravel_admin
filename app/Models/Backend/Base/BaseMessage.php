<?php
namespace App\Models\Backend\Base;

use Illuminate\Database\Eloquent\Model;

class BaseMessage extends Model
{
    
    protected $table='message';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'mname', // 姓名
        'area', // 地区
        'tel', // 电话
        'content', // 留言内容
        'com', // 留言来源页
        'client', // 客户端
        'ip', // ip地址
        'channel', // 留言板块
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-03-27 02:39:13