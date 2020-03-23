<?php
namespace App\Tools;

trait ApiResult {
    private function respond($data)
    {
        return response()->json($data);
    }
    
    public function error($msg,$code=Constant::ERROR){
        return $this->respond(['code'=>$code,'msg'=>$msg]);
    }
    
    public function success($data,$msg="操作成功",$code=Constant::OK){
        return $this->respond(['code'=>$code,'msg'=>$msg,'data'=>$data]);
    }
}