<?php
namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class BaseSysAdminUser extends Model
{
    
    protected $table='sys_admin_user';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'login_name', // 账号
        'nick_name', // 姓名
        'email', // 邮箱
        'tel', // 电话
        'pwd', // 密码
        'avatr', // 用户头像
        'department_id', // 部门
        'position_id', // 职位 角色
        'city_id', // 城市id
        'status', // 状态 1 正常 -1 锁定
        'project_id', // 归属项目 0系统
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-03-23 03:21:01