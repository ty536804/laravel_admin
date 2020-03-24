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
    
    /*
    * 过滤所有特殊字符的函数
    */
    public function filterStr($strParam)
    {
        $regex = "/\/|\～|\，|\。|\！|\？|\“|\”|\【|\】|\『|\』|\：|\；|\《|\》|\’|\‘|\ |\·|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        return preg_replace($regex, "", $strParam);
    }
    
    /**
     * 过滤表情
     * @param $str 内容
     * @return mixed
     */
    public function filterEmoji($str)
    {
        $str = preg_replace_callback('/./u', function (array $match) {
            return strlen($match[0]) >= 4 ? '' : $match[0];
        }, $str);
        $res = $this->filterStr($str);
        return $res;
    }
    
    //验证是否为合法手机号码
    public function checkMobile($mobile)
    {
        if (strlen($mobile) < 12 && preg_match("/^1{1}\d{10}$/", $mobile)) {
            return true;
        }
        return false;
    }
}