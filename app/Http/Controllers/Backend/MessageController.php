<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\MessageRequest;
use App\Models\Backend\Message;
use App\Services\MessageServices;
use App\Tools\ApiResult;
use Yajra\DataTables\Facades\DataTables;

class MessageController extends Controller
{
    //
    use ApiResult;
    protected $message;
    
    public function __construct(MessageServices $message)
    {
        $this->message = $message;
    }
    
    public function show()
    {
        return view("message.index");
    }
    
    /**
     * @description 留言列表
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @auther caoxiaobin
     */
    public function messageList()
    {
        $list = Message::where('id','>',0);
        $databases = DataTables::eloquent($list);
        return $databases->make(true);
    }
    
    
    /**
     * @description 留言提交
     * @param MessageRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function messageSave(MessageRequest $request)
    {
        if ($request->ajax()) {
            return $this->message->messageSave($request->all());
        }
        return $this->error("操作失败");
    }
}
