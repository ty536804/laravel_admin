<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteRequest;
use App\Models\Admin\Site;
use App\Tools\ApiResult;
use App\Tools\Constant;
use Illuminate\Support\Facades\Cache;

class SiteController extends Controller
{
    //
    use ApiResult;
    
    /**
     * @description 站点信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function show()
    {
        $site = $this->siteInfo();
        return view("admin.site", ['info' => $site]);
    }
    
    /**
     * @description 添加信息
     * @param SiteRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function siteSave(SiteRequest $request)
    {
        if ($request->ajax()) {
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
        return $this->error("操作失败");
    }
    
    /**
     * @description 存入缓存
     * @return mixed
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function siteInfo() {
        return Cache::remember("site_info",Constant::VALID_TIME, function () {
            return Site::first();
        });
    }
}
