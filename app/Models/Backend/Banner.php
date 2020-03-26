<?php
namespace App\Models\Backend;

use App\Models\Backend\Base\BaseBanner;

class Banner extends BaseBanner
{
    public function place()
    {
        return $this->hasOne(BannerPosition::class,"id","bposition");
    }
}//Created at 2020-03-25 06:31:23