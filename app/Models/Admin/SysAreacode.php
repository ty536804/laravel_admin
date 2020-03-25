<?php
namespace App\Models\Admin;

use App\Models\Base\BaseSysAreacode;

class SysAreacode extends BaseSysAreacode
{
    public function province()
    {
        return SysAreacode::select('id','aname')->where("parent_id", '-1')->get();
    }
}//Created at 2020-03-24 05:48:35