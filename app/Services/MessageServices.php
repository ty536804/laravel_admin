<?php
namespace App\Services;

use App\Models\Backend\Message;
use App\Tools\ApiResult;

class MessageServices
{
    use ApiResult;
    /**
     * @description 留言提交
     * @param $request
     * @return mixed
     * @auther caoxiaobin
     * date: 2020-04-01
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
}