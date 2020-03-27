<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Admin\SysAreacode;
use App\Models\Backend\Banner;
use App\Models\Backend\BannerPosition;
use App\Services\AdminUser;
use App\Services\BannerServices;
use App\Tools\ApiResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
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
        $list = Banner::where([['id','>',0]])->with('place')->orderBy('is_show','desc');
        $datatable = DataTables::eloquent($list);
        return $datatable->make(true);
    }
    
    
    /**
     * @description 添加banner详情页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-26
     */
    public function bannerDetail()
    {
        $id = Input::get('id');
        $admin_id = $this->admin->getId();
        if ($id <1) {
            $info = new Banner();
        } else {
            $info = Banner::find($id);
            if (!$info) {
                return back()->withErrors(['图片不存在']);
            }
        }
        $areaCode = new SysAreacode();
        $cities = $areaCode->province();
        $position = BannerPosition::where('is_show',1)->get();
        $data = [
            'admin_id' => $admin_id,
            'info' => $info,
            'cities'=>$cities,
            'position' => $position,
        ];
        return view("banner.detail", $data);
    }
    
    /**
     * @description banner操作
     * @auther caoxiaobin
     * date: 2020-03-25
     */
    public function bannerSave(Request $request) {
        if ($request->ajax()) {
            return $this->banner->bannerSave($request->all());
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description 图片删除
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-26
     */
    public function bannerDel(Request $request)
    {
        if ($request->ajax()) {
            $id= $request->post("id",0);
            if ($id < 1) {
              return $this->error("操作失败");
            }
            
            if (!Banner::where("id",$id)->exists()) {
                return $this->error("图片不存在");
            }
            
            DB::table("banner")->where("id","=",$id)->delete();
          return  $this->success("操作成功");
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description 轮播图展示位置
     * @auther caoxiaobin
     * date: 2020-03-25
     */
    public function positionList() {
        return view("banner.list");
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
     * @description 轮播图展示位置添加/编辑
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
