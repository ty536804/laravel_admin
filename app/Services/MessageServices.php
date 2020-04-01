<?php
namespace App\Services;

use App\Models\Backend\Message;

class MessageServices
{
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
        } else {
            return $this->error("留言失败");
        }
    }
    
    /**
     * @description 获取一条留言
     * @auther caoxiaobin
     * date: 2020-04-01
     */
    public function getOneMessage()
    {
    
    }
}