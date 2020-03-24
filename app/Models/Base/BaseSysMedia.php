<?php
namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class BaseSysMedia extends Model
{
    
    protected $table='sys_media';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'uid', // 所有者
        'm_type', // 媒体文件分类
        'm_format', // 媒体文件格式
        'm_name', // 媒体文件分类原名
        'm_url', // 媒体文件存储地址
        'm_size', // 文件大小
        'm_width', // 文件宽度
        'm_height', // 文件高度
        'm_qiniu', // 七牛文件同步状态
        'm_metadata', // 文件原始数据
        'm_status', // 文件状态
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-03-24 05:48:09