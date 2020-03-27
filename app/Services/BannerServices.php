<?php
namespace App\Services;

use App\Models\Backend\Banner;
use App\Models\Backend\BannerPosition;
use App\Tools\ApiResult;
use Illuminate\Support\Facades\Log;

class BannerServices
{
    use ApiResult;
    
    /**
     * @description 编辑banner
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-25
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
            $banner= Banner::find($id);
        }
        
        $banner->fill($data);
        if ($banner->save()) {
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description banner位置
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-26
     */
    public function positionSave($data) {
        $position_name = $data['position_name'] ?? "";
        if (empty($position_name)) {
            return $this->error("位置名称不能为空");
        }
    
        $image_size = $data['image_size'] ?? "";
        if (empty($image_size)) {
            return $this->error("图片大小不能为空");
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
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }
}