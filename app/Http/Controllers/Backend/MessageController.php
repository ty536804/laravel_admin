<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\MessageRequest;
use App\Models\Backend\Message;
use App\Tools\ApiResult;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class MessageController extends Controller
{
    //
    use ApiResult;
    
    public function show(){
        return view("message.index");
    }
    
    /**
     * @description 留言列表
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @auther caoxiaobin
     * date: 2020-03-26
     */
    public function messageList() {
        $list = Message::where('id','>',0);
        $databases = DataTables::eloquent($list);
        return $databases->make(true);
    }
    
    
    /**
     * @description 留言提交
     * @param MessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-26
     */
    public function messageSave(MessageRequest $request) {
        if ($request->ajax()) {
            $data = $request->all();
            $message = new Message();
            $data['ip'] = $request->getClientIp();
            $message->fill($data);
            if ($message->save()) {
                return $this->success("留言成功");
            } else {
                return $this->error("留言失败");
            }
        } else {
            return $this->error("操作失败");
        }
    }
    
    /**
     * @description 留言详情页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-26
     */
    public function messageDetail() {
        $id = Input::get("id");
        if ($id < 1) {
            $article = Message::find($id);
            if (!$article) {
                return back()->withErrors("详情页面不存在");
            }
        }
        return view("message.detail");
    }
}
