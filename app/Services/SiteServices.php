<?php
namespace App\Services;

use App\Models\Admin\Site;
use App\Tools\ApiResult;
use App\Tools\Constant;
use Illuminate\Support\Facades\Cache;

class SiteServices
{
    use ApiResult;
    
    /**
     * @description 添加站点
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-04-01
     */
    public function siteSave($request)
    {
        $id = $request->post("id", 0);
        if ($id < 1) {
            $site = new Site();
        } else {
            $site = Site::find($id);
        }
        $site->fill($request->all());
        if ($site->save()) {
            Cache::forget("site_info");
            return $this->success("操作成功");
        } else {
            return $this->error("操作失败");
        }
    }
    
    /**
     * @description 存入缓存
     * @return mixed
     * @auther caoxiaobin
     */
    public function siteInfo() {
        return Cache::remember("site_info",Constant::VALID_TIME, function () {
            return Site::first();
        });
    }
}