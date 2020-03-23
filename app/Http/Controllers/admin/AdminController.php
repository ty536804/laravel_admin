<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\SysAdminLoginLog;
use App\Models\Admin\SysAdminPosition;
use App\Models\Admin\SysAdminUser;
use App\Services\AdminUser;
use App\Tools\ApiResult;
use App\Tools\Constant;
use App\Tools\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    //
    use ApiResult;
    
    private $uid=0;
    private $adminUser;
    public function __construct(AdminUser $user)
    {
        $this->adminUser =  $user;
    }
    
    /**
     * @description 登录页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-23
     */
    public function loginViews() {
        return view("admin.login");
    }
    
    /**
     * @description 登录
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-23
     */
    public function login(Request $request) {
        $result =new Result();
        if($request->ajax()) {
            try {
                $this->adminUser->forget();
                $login_name = $request->get("login_name");
                $user =  SysAdminUser::where('login_name',$login_name)->where('status',1)->first();
//                Log::info('-------$login_name------'.json_encode($login_name));
//                Log::info('-------$user------'.json_encode(explode(',',$user->position_id)));
                if(!empty($login_name) && !empty($user)){
                    if($user->pwd  == md5($request->get('pwd'))){
                        $ip=$request->getClientIp();
                        $position = SysAdminPosition::whereIn('id',explode(',',$user->position_id))->get();
                        $position_name=$position_power ="";
                        if (!empty($position)){
                            foreach ($position as $v){
                                $position_name .=$v->position_name.',';
                                $position_power .=$v->powerid;
                            }
                        }
                        $position_name=rtrim($position_name, ',');
                        SysAdminLoginLog::insert(['admin_id'=>$user->id,'login_name'=>$user->nick_name,'login_role'=>$position_name
                            ,'client_ip'=>$ip,'browser_info'=>$this->get_broswer(),'os_info'=>$this->get_os(),'created_at'=>date('Y-m-d H:i:s')]);
                        unset($user->pwd);
                        Log::info("Login= ".json_encode($user));
                        $this->adminUser->setId($user->id);
                        $userInfo = SysAdminUser::where('id',$user->id)->with('department')->first();
                        $userInfo->position=$position;
                        $userInfo->position_power=$position_power;
                        $userInfo->position_name=$position_name;
                        unset($userInfo->pwd);
                        $this->adminUser->setUser($userInfo);
                        $log_route =  Cache::get("landingRouting") ?? 1;
                        Cache::forget('landingRouting');
                        $result->code = Constant::OK;
                        $result->msg = "登录成功！";
                        $result->data = $log_route;
                        Log::info("Login ID =".$this->adminUser->getId());
                    }else{
                        $result->msg = "密码不正确";
                    }
                }else{
                    $result->msg  = "账号不存在";
                }
            } catch (\Exception $e) {
                $result->msg = "操作失败";
            }
        }else{
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
    
    /**
     * @description 后端首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-23
     */
    public function index() {
        return view("admin.home");
    }
    
    public function getIndex(){
        Session::forget("ACTIVE_MAINMENU");
        Session::forget("ACTIVE_SUBMENU");
        if(Session::has(Constant::$SESSION_USER)){
            return view('Admin.index');
        }else{
            return view('Admin.login');
        }
    }
    
    /**
     * 退出登录
     * @return array
     */
    public function logout(){
        $this->adminUser->forget();
        return view("admin.login");
    }
    
    public function getSet(){
        $account = SysAdminUser::find($this->uid);
        $data['userinfo'] =  $account;
        return view('Admin.set',$data);
    }
    
    /**
     * @return string
     * @description 区分浏览器
     * @auther al
     */
    function get_broswer()
    {
        $sys = $_SERVER['HTTP_USER_AGENT'];  //获取用户代理字符串
        Log::info('-$sys--'.json_encode($sys));
        if (stripos($sys, "Firefox/") > 0) {
            preg_match("/Firefox\/([^;)]+)+/i", $sys, $b);
            $exp[0] = "Firefox";
            $exp[1] = $b[1];  	//获取火狐浏览器的版本号
        } elseif (stripos($sys, "Maxthon") > 0) {
            preg_match("/Maxthon\/([\d\.]+)/", $sys, $aoyou);
            $exp[0] = "傲游";
            $exp[1] = $aoyou[1];
        } elseif (stripos($sys, "MSIE") > 0) {
            preg_match("/MSIE\s+([^;)]+)+/i", $sys, $ie);
            $exp[0] = "IE";
            $exp[1] = $ie[1];  //获取IE的版本号
        } elseif (stripos($sys, "OPR") > 0) {
            preg_match("/OPR\/([\d\.]+)/", $sys, $opera);
            $exp[0] = "Opera";
            $exp[1] = $opera[1];
        } elseif(stripos($sys, "Edge") > 0) {
            //win10 Edge浏览器 添加了chrome内核标记 在判断Chrome之前匹配
            preg_match("/Edge\/([\d\.]+)/", $sys, $Edge);
            $exp[0] = "Edge";
            $exp[1] = $Edge[1];
        } elseif (stripos($sys, "Chrome") > 0) {
            preg_match("/Chrome\/([\d\.]+)/", $sys, $google);
            $exp[0] = "Chrome";
            $exp[1] = $google[1];  //获取google chrome的版本号
        } elseif(stripos($sys,'rv:')>0 && stripos($sys,'Gecko')>0){
            preg_match("/rv:([\d\.]+)/", $sys, $IE);
            $exp[0] = "IE";
            $exp[1] = $IE[1];
        }else {
            $exp[0] = "未知浏览器";
            $exp[1] = "";
        }
        return $exp[0].'('.$exp[1].')';
    }
    
    /**
     * @return bool|string
     * @description 区别操作系统
     * @auther al
     */
    function get_os(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        Log::info('----$agent----'.json_encode($agent,300));
        $os = false;
        
        if (preg_match('/win/i', $agent) && strpos($agent, '95'))
        {
            $os = 'Windows 95';
        }
        else if (preg_match('/win 9x/i', $agent) && strpos($agent, '4.90'))
        {
            $os = 'Windows ME';
        }
        else if (preg_match('/win/i', $agent) && preg_match('/98/i', $agent))
        {
            $os = 'Windows 98';
        }
        else if (preg_match('/win/i', $agent) && preg_match('/nt 6.0/i', $agent))
        {
            $os = 'Windows Vista';
        }
        else if (preg_match('/win/i', $agent) && preg_match('/nt 6.1/i', $agent))
        {
            $os = 'Windows 7';
        }
        else if (preg_match('/win/i', $agent) && preg_match('/nt 6.2/i', $agent))
        {
            $os = 'Windows 8';
        }else if(preg_match('/win/i', $agent) && preg_match('/nt 10.0/i', $agent))
        {
            $os = 'Windows 10';
        }else if (preg_match('/win/i', $agent) && preg_match('/nt 5.1/i', $agent))
        {
            $os = 'Windows XP';
        }
        else if (preg_match('/win/i', $agent) && preg_match('/nt 5/i', $agent))
        {
            $os = 'Windows 2000';
        }
        else if (preg_match('/win/i', $agent) && preg_match('/nt/i', $agent))
        {
            $os = 'Windows NT';
        }
        else if (preg_match('/win/i', $agent) && preg_match('/32/i', $agent))
        {
            $os = 'Windows 32';
        }
        else if (preg_match('/linux/i', $agent))
        {
            $os = 'Linux';
        }
        else if (preg_match('/unix/i', $agent))
        {
            $os = 'Unix';
        }
        else if (preg_match('/sun/i', $agent) && preg_match('/os/i', $agent))
        {
            $os = 'SunOS';
        }
        else if (preg_match('/ibm/i', $agent) && preg_match('/os/i', $agent))
        {
            $os = 'IBM OS/2';
        }
        else if (preg_match('/Mac/i', $agent) && preg_match('/PC/i', $agent))
        {
            $os = 'Macintosh';
        }
        else if (preg_match('/PowerPC/i', $agent))
        {
            $os = 'PowerPC';
        }
        else if (preg_match('/AIX/i', $agent))
        {
            $os = 'AIX';
        }
        else if (preg_match('/HPUX/i', $agent))
        {
            $os = 'HPUX';
        }
        else if (preg_match('/NetBSD/i', $agent))
        {
            $os = 'NetBSD';
        }
        else if (preg_match('/BSD/i', $agent))
        {
            $os = 'BSD';
        }
        else if (preg_match('/OSF1/i', $agent))
        {
            $os = 'OSF1';
        }
        else if (preg_match('/IRIX/i', $agent))
        {
            $os = 'IRIX';
        }
        else if (preg_match('/FreeBSD/i', $agent))
        {
            $os = 'FreeBSD';
        }
        else if (preg_match('/teleport/i', $agent))
        {
            $os = 'teleport';
        }
        else if (preg_match('/flashget/i', $agent))
        {
            $os = 'flashget';
        }
        else if (preg_match('/webzip/i', $agent))
        {
            $os = 'webzip';
        }
        else if (preg_match('/offline/i', $agent))
        {
            $os = 'offline';
        }
        else if (preg_match('/Mac OS X/i', $agent))
        {
            $os = 'Mac OS X';
        }
        else
        {
            $os = '未知操作系统';
        }
        return $os;
    }
}
