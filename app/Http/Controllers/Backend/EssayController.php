<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\Essay;
use App\Services\BannerServices;
use App\Services\EssayServices;
use App\Tools\ApiResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class EssayController extends Controller
{
    //
    use ApiResult;
    protected $banner;
    protected $essay;
    
    public function __construct(BannerServices $banner,EssayServices $essay)
    {
        $this->banner = $banner;
        $this->essay = $essay;
    }
    
    /**
     * @description 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther xiaobin
     */
    public function index()
    {
        return view("essay.index");
    }
    
    /**
     * @description 详情页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function essayDetail() {
        $result = $this->essay->essayDetail(Input::get("id"));
        return view("essay.detail", $result->data);
    }
    
    /**
     * @description 添加文章/图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function essayAdd(Request $request) {
        if ($request->ajax()) {
            return $this->essay->essaySave($request->all());
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description
     * @auther caoxiaobin
     */
    public function easyList()
    {
        $input = Input::all();
        $id = $input['id'] ?? 1;
        $list = Essay::where([['id','>',0], ['essay_status','=',1], ['banner_position_id','=',$id]])->with('posi');
        $datatable = DataTables::eloquent($list);
        return $datatable->make(true);
    }
    
    /**
     * @description 删除信息
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function essayDel(Request $request) {
        if ($request->ajax()) {
            return $this->essay->essayDel($request->get("id", 0));
        } else {
            return $this->error("操作失败");
        }
    }
    
    /**
     * @description 关于魔数
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function aboutMagic() {
        return view("essay.magic");
    }
    
    /**
     * @description 课程体系
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function course() {
        return view("essay.course");
    }
    
    /**
     * @description 教学
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function research()
    {
        return view("essay.Research");
    }
    
    /**
     * @description AI学习平台
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function learn()
    {
        return view("essay.learn");
    }
    
    /**
     * @description OMO模式
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function omoMode(){
        return view("essay.omo_mode");
    }
    
    /**
     * @description 全国校区
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function all( ) {
        return view("essay.all");
    }
    
    /**
     * @description 加盟授权
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function join(){
        return view("essay.join");
    }
    
    /**
     * @description APP下载
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function appDown(){
        return view("essay.down");
    }
}
