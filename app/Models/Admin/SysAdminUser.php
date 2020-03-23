<?php
namespace App\Models\Admin;

use App\Models\Base\BaseSysAdminDepartment;
use App\Models\Base\BaseSysAdminPosition;
use App\Models\Base\BaseSysAdminUser;

class SysAdminUser extends BaseSysAdminUser
{
    public function  position()
    {
        return $this->hasOne(BaseSysAdminPosition::class,'id','position_id');
    }
    public function  department()
    {
        return $this->hasOne(BaseSysAdminDepartment::class,'id','department_id');
    }

}//Created at 2020-03-23 03:21:23