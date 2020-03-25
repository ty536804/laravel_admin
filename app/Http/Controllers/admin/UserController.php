<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminUserRequest;
use App\Models\Admin\SysAdminPosition;
use App\Models\Admin\SysAdminUser;
use App\Models\Base\BaseSysAdminDepartment;
use App\Models\Base\BaseSysAdminPosition;
use App\Models\Base\BaseSysAdminUser;
use App\Services\AdminMethod;
use App\Services\AdminUser;
use App\Services\UserServices;
use App\Tools\Constant;
use App\Tools\Result;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    //
    private $user;
    protected $result;
    public $adminUser;
    private $adminMethod;
    
    public function __construct(AdminUser $admin,UserServices $user,AdminMethod $adminMethod)
    {
        $this->user = $user;
        $this->adminUser =  $admin;
        $this->result = new Result();
        $this->adminMethod = $adminMethod;
    }
    
    /**
     * @description 修改密码
     * @param Request $request
     * @return Result|\Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-24
     */
    public function change(Request $request) {
        if ($request->ajax()) {
            return $this->user->editPwd($request->all());
        } else {
            $result = $this->result->msg = "Invalid Request";
            return response()->json($result);
        }
    }
    
    
    /**
     * @description 修改用户信息
     * @param Request $request
     * @return Result|\Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-24
     */
    public function updateInfo(Request $request) {
        if ($request->ajax()) {
            return $this->user->updateInfo($request->all());
        } else {
            $result = $this->result->msg = "Invalid Request";
            return response()->json($result);
        }
    }
    
    public function list(Request $request){
        $id=$request->get('id',0);
        if($id!=0){
            $user = BaseSysAdminUser::find($id);
        }else{
            $user = new BaseSysAdminUser();
        }
        $data['city'] =$this->adminMethod->cityNameAll();
        $data['city_names'] =$this->adminMethod->cityNameAllJson();
        $data['info']=$user;
        $data['dp_name']= BaseSysAdminDepartment::all('id','dp_name');
        $data['pt_name'] =BaseSysAdminPosition::all('id','position_name');
        return view('admin.user_list',$data);
    }
    
    public function getListData(){
        $list = SysAdminUser::where('sys_admin_user.id','>',0)->select('sys_admin_user.id','nick_name','email','tel','login_name',
            'sys_admin_user.city_id','sys_admin_user.department_id','sys_admin_user.position_id',
            'sys_admin_user.status','sys_admin_position.position_name','sys_admin_department.dp_name','sys_areacode.aname as city_name')
            ->leftjoin('sys_admin_department','sys_admin_department.id','=','sys_admin_user.department_id')
            ->leftjoin('sys_admin_position','sys_admin_position.id','=','sys_admin_user.position_id')
            ->leftjoin('sys_areacode','sys_areacode.aid','=','sys_admin_user.city_id');
        $datatable =DataTables::eloquent($list);
        return $datatable->make(true);
    }
    
    public function save(AdminUserRequest $request){
        $result =new Result();
        if($request->ajax()) {
            try {
                if($request->id>0){
                    $admin_user  = SysAdminUser::find($request->id);
                    $admin = SysAdminUser::find($request->id);
                }else{
                    $admin_user  = new SysAdminUser();
                    $admin="";
                }
                if (in_array(10000,explode(',',$request->city_id))){
                    if (count(explode(',',$request->city_id)) != 1){
                        $result->msg = "添加全国不可选择分城市";
                        $result->code =  Constant::ERROR;
                        return response()->json($result);
                    }
                }
                $admin_user->fill($request->all());
                if (empty($request->id) || !empty($request->pwd)){
                    $admin_user->pwd =  md5($request->get('pwd'));
                }else{
                    $admin_user->pwd = $admin->pwd;
                }
                $admin_user->position_id = $request->position_id ?? 0;
                $admin_user->save();
                $result->msg = "操作成功";
                $result->code =  Constant::OK;
            } catch (\Exception $e) {
                $result->msg = "操作成功";
            }
        }else{
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
    
    
    public function delete(Request $request){
        $result =new Result();
        if($request->ajax()) {
            try {
                if($request->id>0){
                    SysAdminUser::find($request->id)->update(['status'=>$request->status]);
                }
                $result->msg = "操作成功";
                $result->code =  Constant::OK;
            } catch (\Exception $e) {
                $result->msg = "操作成功";
            }
        }else{
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
  
    
    /**
     * 修改资料
     */
    public function set_save(Request $request) {
        $result =new Result();
        if($request->ajax()) {
            try {
                $power  = SysAdminUser::find($request->id);
                if ( $power->pwd == md5($request->pwd)){
                    SysAdminUser::find($request->id)->update(['pwd'=>md5($request->newpwd)]);
                    $result->msg = "操作成功";
                    $result->code =  Constant::OK;
                }else{
                    $result->msg  = "密码错误";
                }
            } catch (\Exception $e) {
                $result->msg = "操作成功";
            }
        }else{
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
    
    /***
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description 添加级联效果 通过部门编号获得 职位信息  这个地方需要重新写
     */
    public function linkage(Request $request){
        $department_id = $request->department_id ? $request->department_id : 1;
        $position= SysAdminPosition::select('id','position_name')->where('status',1)->where('department_id',$department_id)->get();
        return response()->json($position);
    }
}
