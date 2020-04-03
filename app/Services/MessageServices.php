<?php
namespace App\Services;

use App\Models\Backend\Message;
use App\Tools\ApiResult;
use Carbon\Carbon;

class MessageServices
{
    use ApiResult;
    /**
     * @description 留言提交
     * @param $request
     * @return mixed
     * @auther caoxiaobin
     */
    function messageSave($request) {
        $message = new Message();
        $data['ip'] = $request->getClientIp();
        $message->fill($data);
        if ($message->save()) {
            return $this->success("留言成功");
        }
        return $this->error("留言失败");
    }
    
    /**
     * @description 统计当日留言数量
     * @return mixed
     * @auther caoxiaobin
     */
    function totalMessage()
    {
        $dayParam = [
            ['created_at', '>=', Carbon::now()->startOfDay()],
            ['created_at', '<=', Carbon::now()->endOfDay()]
        ];
        $countDay = $this->countMessage($dayParam);
    
        $yesterday = date("Y-m-d", strtotime("-1day"));
        $yesterdayParam = [
            ['created_at', '>=', $yesterday." 00:00:00"],
            ['created_at', '<=', $yesterday." 23:59:59"]
        ];
        $countYesterday = $this->countMessage($yesterdayParam);
        return [
            "day" => $countDay,
            "yesterday" => $countYesterday
        ];
    }
    
    /**
     * @description 比例
     * @param $val
     * @param $val2
     * @return int
     * @auther caoxiaobin
     * date: 2020-04-01
     */
    public function rate($val, $val2)
    {
        if ($val == 0 && $val2 ==0) {
            return 0;
        }
        if ($val ==0) {
            return 0;
        }
    }
    
    /**
     * @description 获取某日的总数
     * @param $param
     * @return mixed
     * @auther caoxiaobin
     */
    public function countMessage($param)
    {
        return Message::where($param)->count();
    }
}