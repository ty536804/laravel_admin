<?php
namespace App\Services;

use App\Models\Backend\BannerPosition;
use App\Models\Backend\Essay;
use App\Tools\ApiResult;
use App\Tools\Constant;
use App\Tools\Result;
use Illuminate\Support\Facades\Cache;

class EssayServices
{
    use ApiResult;
    
    protected $admin;
    protected $result;
    public function __construct(AdminUser $admin)
    {
        $this->admin = $admin;
        $this->result = new Result();
    }
    
    /**
     * @description 获取详情
     * @param $id
     * @return Result
     * @auther caoxiaobin
     */
    public function essayDetail($id)
    {
        if ($id < 1) {
            $info = new Essay();
        } else {
            $info = $this->essayOne($id);
        }
        
        $position = BannerPosition::where('is_show',1)->get();
        $data = [
            'admin_id' => $this->admin->getId(),
            'info' => $info,
            'position' => $position,
        ];
        $this->result->code = Constant::OK;
        $this->result->msg = "操作成功";
        $this->result->data = $data;
        return $this->result;
    }
    
    /**
     * @description 新增/编辑内容
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function essaySave($data)
    {
        $id = $data['id'] ?? 0;
        if ($id < 1) {
            $essay = new Essay();
        } else {
            $essay = $this->essayOne($id);
        }
        
        $essay_img_info = $data['essay_img_info'];
        $picList = [];
        if (!empty($essay_img_info)) {
            $picInfo = json_decode($data['essay_img_info'],true);
            foreach ($picInfo as $pic) {
                array_push($picList, $pic['m_url']);
            }
            $data['essay_img'] = implode(",",$picList);
        }
        $essay->fill($data);
        if ($essay->save()) {
            Cache::forget("essay_list{$essay->banner_position_id}");
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description 删除某篇文章
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function essayDel($id) {
        if ($id < 1) {
            return $this->error("操作失败");
        }
        $essay = $this->essayOne($id);
        if (!$essay) {
            return $this->error("内容不存在");
        }
        $essay->essay_status = 0;
        if ($essay->save()) {
            Cache::forget("essay_list{$essay->banner_position_id}");
            return $this->success("操作成功");
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description 通过ID获取一条记录
     * @param $id 文章ID
     * @return mixed
     * @auther caoxiaobin
     */
    public function essayOne($id) {
        return Essay::find($id);
    }
}