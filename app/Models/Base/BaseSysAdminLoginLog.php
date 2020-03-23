<?php
namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class BaseSysAdminLoginLog extends Model
{
    
    protected $table='sys_admin_login_log';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'admin_id', // 管理员id
        'login_name', // 管理员名称
        'login_role', // 管理员角色
        'client_ip', // ip
        'browser_info', // 登陆浏览器及版本
        'os_info', // 操作系统信息
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-03-23 03:26:19