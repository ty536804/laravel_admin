<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminDepartmentRequest;
use App\Models\Admin\SysAdminDepartment;
use App\Models\Admin\SysAdminPower;
use App\Models\Base\BaseSysAdminPower;
use App\Services\AdminUser;
use App\Tools\Constant;
use App\Tools\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;

class DepartmentController extends Controller
{
    //
    public $adminUser;
    
    function __construct(AdminUser $user)
    {
        $this->adminUser =  $user;
    }
    
    public function list(){
        //部门SysAdminDepatment
        $department = SysAdminDepartment::where([['parent_id',0],['status',1]])->with(['children'=>function($query){
            $query->where('status',1);
        }])->get();
        $top_department = json_encode($department->toArray());
        $all = SysAdminDepartment::all()->keyBy('id');
        
        //权限
        $power = SysAdminPower::where('parent_id',0)->with('allchild')->get();
        return view('admin.department_list',['department'=>$department,'power'=>$power,'all'=>$all,'top_department'=>$top_department]);
    }
    
    public function view(Request $request) {
        $id=$request->get('id',0);
        
        if (empty($request->level)) {
            $data['level'] = 0;
        } else {
            $data['level'] =$request->level;
        }
        if (empty($request->parent_id)) {
            $data['parent_id'] = 0;
        } else {
            $data['parent_id'] =$request->parent_id;
        }
        if ($id!=0) {
            $department = SysAdminDepartment::find($id);
            $department->powerid = explode('|',$department->powerid);
        } else {
            $department = new SysAdminDepartment();
            if ($request->pid>0) {
                $data['dp_name'] = SysAdminDepartment::all('id','dp_name');
                $department->pid = $request->pid;
            }
        }
        
        $power_name = BaseSysAdminPower::all('id','pname','parent_id');
        $data['power_name']  = $power_name;
        
        $data['info'] = $department;
        $powerid = $department->powerid ? array_filter($department->powerid) : '';
        $data['powerid'] = $powerid;
        
        $sysAdminPower_model = new SysAdminPower();
        $getTree = $sysAdminPower_model->getTree($power_name);
        $data['department_rule'] = $getTree;
        
        return view("admin.department_view",$data);
    }
    
    public function update(Request $request) {
        $result =new Result();
        if ($request->ajax()) {
            try {
                $id = Input::get('id');
                $department = new SysAdminDepartment();
                if (!empty($request->powerid) && empty($id)){
                    if (empty($request->position_name)){
                        $result->code=Constant::ERROR;
                        $result->msg="请选择部门";
                        return response()->json($result);
                    }
                }
                if (!empty($id)) {
                    if (!empty($request->dp_name)) {
                        $po_name_arr = $department->where('id','!=',$id)->pluck('dp_name')->toArray();
                        if (in_array($request->dp_name,$po_name_arr)){
                            $result->msg="部门名称不能重复";
                            return response()->json($result);
                        }
                    } else {
                        if (empty($request->powerid)) {
                            $result->code = Constant::ERROR;
                            $result->msg = "请正确操作";
                            return response()->json($result);
                        }
                    }
                    $dept  = SysAdminDepartment::find($request->id);
                    if (!empty($dept)) {
                        $dept->fill($request->all());
                        $dept->save();
                        $result->code=Constant::OK;
                        $result->msg="操作成功";
                    }
                } else {
                    if (!empty($request->dp_name)) {
                        $po_name_arr = $department->pluck('dp_name')->toArray();
                        if (in_array($request->dp_name,$po_name_arr)){
                            $result->msg="部门名称不能重复";
                            return response()->json($result);
                        }
                    }
                    if (empty($request->dp_name) && empty($request->powerid)){
                        $result->code=Constant::ERROR;
                        $result->msg="请正确操作";
                        return response()->json($result);
                    }
                    $dept  = new SysAdminDepartment();
                    $dept->fill($request->all());
                    $dept->save();
                    $result->code=Constant::OK;
                    $result->msg="操作成功";
                }
            } catch (\Exception $e) {
                $result->msg = "操作失败";
            }
        } else {
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
    
    public function save(AdminDepartmentRequest $request) {
        Log::error("====================".json_encode(Input::all()));
        $result =new Result();
        if ($request->ajax()) {
            try {
                if($request->id>0){
                    $power  = SysAdminDepartment::find($request->id);
                }else{
                    $power  = new SysAdminDepartment();
                }
                Log::error("====================".json_encode($request->powerid));
                $power->fill(Input::all());
                $power->powerid='|';
                $power->save();
                Log::error("=========power===========".json_encode($power));
                Log::error("=========level===========".json_encode($request->level));
                if ($request->level == 0){
                    $power->root_id = $power->id;
                    $power->path = "|".$power->id."|";
                    $power->level = 1;
                } else {
                    if (empty($request->parent_id)) {
                        $result->msg = "请从左侧选择父级";
                        $result->code =  Constant::ERROR;
                        return response()->json($result);
                    }
                    $parent  =  SysAdminDepartment::find($request->parent_id);
                    Log::error("=========parent===========".json_encode($parent));
                    $power->root_id = $parent->root_id;
                    $power->path = $parent->path.$power->id."|";
                    $power->level = $parent->level+1;
                }
                $power->save();
                
                $result->msg = "操作成功";
                $result->code =  Constant::OK;
            } catch (\Exception $e) {
                $result->msg = "操作失败";
            }
        } else {
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
    
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @description
     */
    public function delete(Request $request) {
        $result =new Result();
        if ($request->ajax()) {
            try {
                if ($request->id>0) {
                    SysAdminDepartment::find($request->id)->update(['status'=> 0]);
                }
                $result->msg = "操作成功";
                $result->code =  Constant::OK;
            } catch (\Exception $e) {
                $result->msg = "操作失败";
            }
        } else {
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
}
