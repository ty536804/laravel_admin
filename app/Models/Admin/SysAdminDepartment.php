<?php
namespace App\Models\Admin;

use App\Models\Base\BaseSysAdminDepartment;

class SysAdminDepartment extends BaseSysAdminDepartment
{
    /**
     * @description 部门 一对多
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @auther xiaobin
     */
    public function child(){
        return $this->hasMany(SysAdminDepartment::class,'parent_id','id');
    }
    
    public function children()
    {
        return $this->child()->with('children');
    }
}