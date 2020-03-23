<?php
namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class BaseSysAdminPosition extends Model
{
    
    protected $table='sys_admin_position';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'position_name', // 职位名称
        'department_id', // 归属部门
        'desc', // 职位描述
        'powerid', // 职位权限
        'status', // 职位状态 1 正常
        'city_id', // 城市id
        'created_at', // 
        'updated_at', // 
    ];
}//Created at 2020-03-23 03:33:28