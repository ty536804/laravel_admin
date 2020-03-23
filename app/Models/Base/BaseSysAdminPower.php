<?php
namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class BaseSysAdminPower extends Model
{
    
    protected $table='sys_admin_power';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'pname', // 权限名称
        'ptype', // 1 左侧菜单 2顶部菜单
        'icon', // 权限ICON样式名称
        'desc', // 权限描述
        'purl', // 权限地址
        'parent_id', // 上级地址
        'pindex', // 显示排序
        'status', // 状态 1 显示 0不显示
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-03-23 03:33:38