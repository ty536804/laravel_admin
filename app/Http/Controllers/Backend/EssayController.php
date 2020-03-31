<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Backend\BannerPosition;
use App\Models\Backend\Essay;
use App\Services\AdminUser;
use App\Services\BannerServices;
use App\Tools\ApiResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class EssayController extends Controller
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
    
    public function index()
    {
        return view("essay.index");
    }
    
    /**
     * @description 详情页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-30
     */
    public function essayDetail() {
        $id = Input::get("id");
        if ($id <1) {
            $info = new Essay();
        } else {
            $info = Essay::find($id);
            if (!$info) {
                return back()->withErrors(['内容不存在']);
            }
        }
        $position = BannerPosition::where('is_show',1)->get();
        $data = [
            'admin_id' => $admin_id = $this->admin->getId(),
            'info' => $info,
            'position' => $position,
        ];
        return view("essay.detail", $data);
    }
    
    /**
     * @description 添加文章/图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-30
     */
    public function essayAdd(Request $request) {
        if ($request->ajax()) {
            $id = $request->post("id",0);
            if ($id < 1) {
                $essay = new Essay();
            } else {
                $essay = Essay::find($id);
                if (!$essay) {
                    return $this->error("已删除，不能编辑");
                }
            }
            $picList = [];
            $data = $request->all();
            if (!empty($data['essay_img_info'])) {
                $picInfo = json_decode($request->post("essay_img_info"),true);
                foreach ($picInfo as $pic) {
                    array_push($picList, $pic['m_url']);
                }
                $data['essay_img'] = implode(",",$picList);
            }
            $essay->fill($data);
            if ($essay->save()) {
                return $this->success("操作成功");
            } else {
                return $this->error("操作失败");
            }
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description
     * @auther caoxiaobin
     * date: 2020-03-31
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
     * date: 2020-03-31
     */
    public function essayDel(Request $request) {
        if ($request->get("id")) {
            $id = $request->get("id", 0);
            if ($id < 1) {
                return $this->error("操作失败");
            }
            $essay = Essay::find($id);
            if (!$essay) {
                return $this->error("不存在当前文章");
            }
            $essay->essay_status = 0;
            if ($essay->save()) {
                return $this->success("操作成功");
            } else {
                return $this->error("操作失败");
            }
        } else {
            return $this->error("操作失败");
        }
    }
    
    /**
     * @description 关于魔数
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function aboutMagic() {
        return view("essay.magic");
    }
    
    /**
     * @description 课程体系
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function course() {
        return view("essay.course");
    }
    
    /**
     * @description 教学
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function research()
    {
        return view("essay.Research");
    }
    
    /**
     * @description AI学习平台
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function learn()
    {
        return view("essay.learn");
    }
    
    /**
     * @description OMO模式
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function omoMode(){
        return view("essay.omo_mode");
    }
    
    /**
     * @description 全国校区
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function all( ) {
        return view("essay.all");
    }
    
    /**
     * @description 加盟授权
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function join(){
        return view("essay.join");
    }
    
    /**
     * @description APP下载
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-31
     */
    public function appDown(){
        return view("essay.down");
    }
}
