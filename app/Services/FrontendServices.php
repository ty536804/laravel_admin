<?php
namespace App\Services;

use App\Models\Backend\Banner;
use App\Models\Backend\Essay;
use App\Tools\Constant;
use Illuminate\Support\Facades\Cache;

class FrontendServices
{
    
    /**
     * @description 获取某个导航下的所有banner
     * @param $id int 导航ID
     * @return mixed
     * @auther caoxiaobin
     */
    public function banner($id)
    {
        return Cache::remember("banner_list{$id}", Constant::VALID_TIME, function () use($id) {
            $param = [
                ['is_show', '=', 1],
                ['bposition', '=', $id],
            ];
            return Banner::select("bposition", "bname", "imgurl", "target_link")->where($param)->get();
        });
    }
    
    /**
     * @description 获取某个导航下面的相关信息
     * @param $id int 导航ID
     * @return mixed
     * @auther caoxiaobin
     */
    public function essay($id)
    {
        return Cache::remember("essay_list{$id}", Constant::VALID_TIME, function () use($id) {
            $param = [
                ['essay_status', '=', 1],
                ['banner_position_id', '=', $id]
            ];
            return Essay::where($param)->get();
        });
    }
}