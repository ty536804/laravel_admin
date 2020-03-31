<?php
namespace App\Models\Admin\Base;

use Illuminate\Database\Eloquent\Model;

class BaseBaseSite extends Model
{
    
    protected $table='base_site';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'site_title', // 网站标题
        'site_desc', // 网站描述
        'site_keyboard', // 网站关键字
        'site_copyright', // 版权
        'site_tel', // 电话
        'site_email', // 邮箱
        'site_address', // 地址
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-03-31 05:58:07