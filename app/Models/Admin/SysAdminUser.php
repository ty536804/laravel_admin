<?php
namespace App\Models\Admin;

use App\Models\Base\BaseSysAdminDepartment;
use App\Models\Base\BaseSysAdminPosition;
use App\Models\Base\BaseSysAdminUser;

class SysAdminUser extends BaseSysAdminUser
{
    /**
     * @description 获取职位
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @auther xiaobin
     */
    public function  position()
    {
        return $this->hasOne(BaseSysAdminPosition::class,'id','position_id');
    }
    
    /**
     * @description 获取部门
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * @auther xiaobin
     */
    public function  department()
    {
        return $this->hasOne(BaseSysAdminDepartment::class,'id','department_id');
    }

}//Created at 2020-03-23 03:21:23