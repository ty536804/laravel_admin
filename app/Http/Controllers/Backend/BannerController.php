<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin\SysAreacode;
use App\Models\Backend\Banner;
use App\Models\Backend\BannerPosition;
use App\Models\Backend\Base\BaseBanner;
use App\Services\AdminUser;
use App\Services\BannerServices;
use App\Tools\ApiResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    //
    use ApiResult;
    protected $banner;
    protected $admin;
    
    public function __construct(BannerServices $banner,AdminUser $admin)
    {
        $this->banner = $banner;
        $this->admin = $admin;
    }
    
    /**
     * @description banner列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-25
     */
    public function index() {
        $areaCode = new SysAreacode();
        $cities = $areaCode->province();
        return view("banner.index", ['cities'=> $cities]);
    }
    
    /**
     * @description banner列表
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @auther caoxiaobin
     * date: 2020-03-25
     */
    public function bannerList()
    {
        $list = BaseBanner::where([['id','>',0]])->orderBy('is_show','desc');
        $datatable = DataTables::eloquent($list);
        return $datatable->make(true);
    }
    
    /**
     * @description 隐藏图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-25
     */
    public function bannerDel(Request $request)
    {
        if ($request->ajax()) {
            return $this->banner->bannerDel($request->post("id",0));
        } else {
            return $this->error("非法操作");
        }
    }
    
    public function bannerDetail()
    {
        $admin_id = $this->admin->getId();
        $info = new Banner();
        $areaCode = new SysAreacode();
        $cities = $areaCode->province();
        return view("banner.detail", ['admin_id' => $admin_id, 'info' => $info, 'cities'=>$cities]);
    }
    /**
     * @description 保存图片
     * @auther caoxiaobin
     * date: 2020-03-25
     */
    public function bannerSave() {
    
    }
    
    /**
     * @description 展示位置
     * @auther caoxiaobin
     * date: 2020-03-25
     */
    public function positionList() {
        return view("banner.list");
    }
    
    
    public function positionEdit(Request $request) {
        if ($request->ajax()) {
        
        }
    }
    
    /**
     * @description 轮播图展示位置ajax
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @auther caoxiaobin
     * date: 2020-03-25
     */
    public function positionData()
    {
        $list = BannerPosition::where('id','>',0);
        $datatable = DataTables::eloquent($list);
        return $datatable->make(true);
    }
    
    
    /**
     * @description 删除轮播图展示位置
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-25
     */
    public function positionDel(Request $request) {
        if ($request->ajax()) {
            $id = $request->get('id',0);
            if ($id <1) {
                return $this->error("操作失败");
            }
            
            $bannerPosition = BannerPosition::find($id);
            if (!$bannerPosition) {
                return $this->error("操作失败");
            }
    
            DB::table("banner_position")->where("id",$id)->delete();
            return $this->success("操作成功");
        } else {
            return $this->error("操作失败");
        }
    }
    
    
    /**
     * @description 轮播图展示位置添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-25
     */
    public function positionSave(Request $request) {
        if ($request->ajax()) {
            return $this->banner->positionSave($request->all());
        }
        return $this->error("操作失败");
    }
}
