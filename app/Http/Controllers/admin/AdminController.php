<?php
namespace App\Http\Controllers\Admin;

use App\Helpers\Logs;
use App\Http\Controllers\Controller;
use App\Models\Admin\SysAdminUser;
use App\Models\Base\BaseSysAdminUser;
use App\Services\AdminUser;
use App\Services\MessageServices;
use App\Services\SysMediaBuild;
use App\Tools\Constant;
use App\Tools\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;


class AdminController extends Controller{
    private $uid=0;
    private $fb;
    private $user;
    protected $message;
    public function __construct(SysMediaBuild $fb,AdminUser $user,MessageServices $message)
    {
        $this->adminUser =  $user;
        $this->fb = $fb;
        $this->user =  $user;
        $this->message = $message;
    }
    
    public function index() {
        $data = $this->message->totalMessage();
        return view('master.home', $data);
    }
    /**
     * 主页
     */
    public function main(){
        return view('master.home');
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @description 登陆
     */
    public function loginViews(){
        return view('admin.login');
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @description 退出
     */
    public function login(Request $request) {
        $result =new Result();
        if($request->ajax()) {
            try {
                $login_name = $request->post("login_name");
                $user =  BaseSysAdminUser::where('login_name',$login_name)->where('status',1)->first();
                if(!empty($login_name) && !empty($user)){
                    if($user->pwd  == md5($request->post('pwd'))){
                        unset($user->pwd);
//                        Log::error("Login= ".json_encode($user));
                        $this->adminUser->setId($user->id);
                        $userInfo = SysAdminUser::where('id',$user->id)->with('department')->with('position')->first();
                        unset($userInfo->pwd);
                        $this->adminUser->setUser($userInfo);
                        $result->code = Constant::OK;
                        $result->msg = "登录成功！";
//                        Log::error("Login ID =".$this->adminUser->getId());
                    }else{
                        $result->msg = "密码不正确";
                    }
                }else{
                    $result->msg  = "账号不存在";
                }
            } catch (\Exception $e) {
                Logs::error('操作失败',$e);
                $result->msg  = "操作失败:".$e->getMessage();
            }
        }else{
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
    /**
     * 退出登录
     * @return array
     */
    public function logout(){
        $this->adminUser->forget();
        return view("admin.login");
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 更新资料
     */
    public function anyUpdate(Request $request){
        $result =new Result();
        if($request->ajax()) {
            try {
                $account = BaseSysAdminUser::find($this->uid);
                if(!empty($account)){
                    $pwd = $request->get("pwd");
                    $newpwd = $request->get('newpwd');
                    $flag  = true;
                    if($pwd!="" && $newpwd!=""){
                        if($account->password == md5($pwd)){
                            $account->password=md5($newpwd);
                        }else{
                            $flag  = false;
                            $result->msg  = "操作失败:原密码输入错误";
                        }
                    }
                    if($flag){
                        $account->avata = $this->fb->getUrl($request->get('img'));
                        $account->uname =  $request->get('uname');
                        $account->save();
                        $result->code = Constant::OK;
                        $result->msg  = "操作成功";
                    }
                }else{
                    $result->msg  = "操作失败:参数缺失";
                }
            } catch (\Exception $e) {
                
                $result->msg  = "操作失败:".$e->getMessage();
            }
        }else{
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
    
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @description 登陆成功主页
     */
    public function getIndex(){
        Session::forget("ACTIVE_MAINMENU");
        Session::forget("ACTIVE_SUBMENU");
        if(Session::has(Constant::$SESSION_USER)){
            return view('admin.index');
        }else{
            return view('admin.login');
        }
    }
    public function getDoc(){
        return view('admin.doc');
    }
    public function getWelcome(){
        return view('admin.welcome');
    }
}
