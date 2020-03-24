<?php
namespace App\Models\Base;

use Illuminate\Database\Eloquent\Model;

class BaseSysAreacode extends Model
{
    
    protected $table='sys_areacode';
    protected $primaryKey='id';
    public $timestamps = true;

    protected $fillable=[
        'aid', // 区域编号
        'a_level', // 节点等级 1 省 2市 3区
        'gaode_id', // 高德ID
        'aname', // 区域名称
        'parent_id', // 父节点
        'a_status', // 1 有效 0 无效
        'root_id', // 根节点ID
        'is_show', // 1 前端显示 0 不显示
    ];
}//Created at 2020-03-24 06:11:02