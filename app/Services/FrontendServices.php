<?php
namespace App\Services;

use App\Models\Backend\Banner;
use App\Models\Backend\Essay;
use App\Tools\Constant;
use Illuminate\Support\Facades\Cache;

class FrontendServices
{
    
    /**
     * @description 获取banner
     * @param $id
     * @return mixed
     * @auther caoxiaobin
     * date: 2020-04-01
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
     * @description 获取信息
     * @param $id
     * @return mixed
     * @auther caoxiaobin
     * date: 2020-04-01
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