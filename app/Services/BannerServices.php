<?php
namespace App\Services;

use App\Models\Admin\SysAreacode;
use App\Models\Backend\Banner;
use App\Models\Backend\BannerPosition;
use App\Tools\ApiResult;
use App\Tools\Constant;
use App\Tools\Result;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class BannerServices
{
    use ApiResult;
    
    protected $result;
    protected $admin;
    public function __construct(AdminUser $admin)
    {
        $this->result = new Result();
        $this->admin = $admin;
    }
    
    /**
     * @description 删除banner
     * @param $id bannerID
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function bannerDel($id)
    {
        if ($id < 1) {
            return $this->error("操作失败");
        }
        $banner = $this->getOneBanner($id);
        if (!$banner) {
            return $this->error("操作失败");
        }
    
        DB::table("banner")->where("id","=",$id)->delete();
        Cache::forget("banner_list{$banner->bposition}");
        return $this->success("操作成功");
    }
    
    /**
     * @description 编辑banner
     * @param $data post提交的所有数据
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function bannerSave($data)
    {
        $id = $data['id'] ?? 0;
        $bname = $data['bname'] ?? "";
        if (empty($bname)) {
            return $this->error("名称不能为空");
        }
        
        $bposition = $data['bposition'] ?? "";
        if (empty($bposition)) {
            return $this->error("显示位置不能为空");
        }
        
        $target_link = $data['target_link'] ?? "";
        if (empty($target_link)) {
            return $this->error("链接不能为空");
        }
        
        $imgurl = $data['img_info'] ?? "";
        
        if (empty($imgurl)) {
            return $this->error("请上传banner图片");
        }
        
        $picInfo = json_decode($imgurl,true);
        $picInfo = reset($picInfo);
        $data['imgurl'] = $picInfo['m_url'];
        
        if ($id < 1) {
            $banner = new Banner();
        } else {
            $banner= $this->getOneBanner($id);
        }
        
        $banner->fill($data);
        if ($banner->save()) {
            Cache::forget("banner_list{$data['bposition']}");
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description banner位置
     * @param $data post提交的所有数据
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function positionSave($data) {
        $position_name = $data['position_name'] ?? "";
        if (empty($position_name)) {
            return $this->error("位置名称不能为空");
        }
    
        $base_url = $data['base_url'] ?? "";
        if (empty($base_url)) {
            return $this->error("基础地址不能为空");
        }
    
        $id = $data['id'] ?? 0;
        if ($id < 1 ) {
            $bannerPosi = new BannerPosition();
        } else {
            $bannerPosi = BannerPosition::find($id);
            if ($data['is_show'] ==2 &&  Banner::where("bposition", $id)->exists()) {
                return $this->error("轮播图取消展示之后放开，关闭");
            }
        }
    
        $bannerPosi->fill($data);
        if ($bannerPosi->save()) {
            Cache::forget("menu");
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description 详情
     * @param $id bannerID
     * @return Result
     * @auther caoxiaobin
     */
    public function bannerDetail($id)
    {
        if ($id < 1) {
            $banner = new Banner();
        } else {
            $banner = $this->getOneBanner($id);
            if (!$banner) {
                $this->result->code = Constant::ERROR;
                $this->result->msg = "操作失败";
                return $this->result;
            }
        }
    
        $admin_id = $this->admin->getId();
        
        $areaCode = new SysAreacode();
        $cities = $areaCode->province();
        $position = $this->menu();
        $data = [
            'admin_id' => $admin_id,
            'info' => $banner,
            'cities'=>$cities,
            'position' => $position,
        ];
        $this->result->code = Constant::OK;
        $this->result->msg = "操作成功";
        $this->result->data = $data;
        return $this->result;
    }
    
    /**
     * @description 获取单个banner
     * @param $id bannerID
     * @return mixed
     * @auther caoxiaobin
     */
    public function getOneBanner($id)
    {
        return Banner::find($id);
    }
    
    /**
     * @description 菜单
     * @return mixed
     * @auther caoxiaobin
     */
    public function menu()
    {
        return Cache::remember("menu",Constant::VALID_TIME, function (){
            return BannerPosition::where("is_show", 1)->get();
        });
    }
}