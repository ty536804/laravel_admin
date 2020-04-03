<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiteRequest;
use App\Services\SiteServices;
use App\Tools\ApiResult;

class SiteController extends Controller
{
    //
    use ApiResult;
    protected $site;
    public function __construct(SiteServices $site)
    {
        $this->site = $site;
    }
    
    /**
     * @description 站点信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function show()
    {
        $site = $this->site->siteInfo();
        return view("admin.site", ['info' => $site]);
    }
    
    /**
     * @description 添加信息
     * @param SiteRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function siteSave(SiteRequest $request)
    {
        if ($request->ajax()) {
            return $this->site->siteSave($request->all());
        }
        return $this->error("操作失败");
    }
}
