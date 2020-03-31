<?php
namespace App\Models\Backend;

use App\Models\Backend\Base\BaseEssay;

class Essay extends BaseEssay
{
    public function posi()
    {
        return $this->hasOne(BannerPosition::class, "id","banner_position_id");
    }
}//Created at 2020-03-30 09:46:34