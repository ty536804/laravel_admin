<?php
namespace App\Services;

use App\Models\Backend\Banner;
use App\Models\Backend\BannerPosition;
use App\Tools\ApiResult;
use App\Tools\Result;
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
    public function bannerDel($id)
    {
        if ($id < 1) {
            return $this->error("操作错误");
        }
        
        $banner = Banner::find($id);
        if (!$banner) {
            Log::info("图片不存在");
            return $this->error("图片不存在");
        }
        
        $banner->is_show = 1;
        if ($banner->save()) {
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }
    
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
            $banner = new BannerPosition();
        } else {
            $banner = BannerPosition::find($id);
        }
        
        $banner->fill($data);
        if ($banner->save()) {
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }
}