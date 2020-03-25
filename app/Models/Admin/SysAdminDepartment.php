<?php
namespace App\Models\Admin;

use App\Models\Base\BaseSysAdminDepartment;

class SysAdminDepartment extends BaseSysAdminDepartment
{
    public function child(){
        return $this->hasMany(SysAdminDepartment::class,'parent_id','id');
    }
    public function children()
    {
        return $this->child()->with('children');
    }
}//Created at 2020-03-23 06:11:16