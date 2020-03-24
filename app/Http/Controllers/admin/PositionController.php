<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPositionRequest;
use App\Models\Admin\SysAdminDepartment;
use App\Models\Admin\SysAdminPower;
use App\Models\Base\BaseSysAdminDepartment;
use App\Models\Base\BaseSysAdminPosition;
use App\Services\AdminUser;
use App\Tools\Constant;
use App\Tools\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller
{
    //
    public $adminUser;
    function __construct(AdminUser $user)
    {
        $this->adminUser =  $user;
    }
    
    public function view(Request $request){
        $id=$request->get('id',0);
        if($id!=0){
            $power = BaseSysAdminPosition::find($id);
            $power->powerid = explode('|',$power->powerid);
        }else{
            $power = new BaseSysAdminPosition();
        }
        $dp_name =BaseSysAdminDepartment::all('id','dp_name');
        $power_name = SysAdminPower::all('id','pname','parent_id');
        
        $sysAdminPower_model = new SysAdminPower();
        $getTree = $sysAdminPower_model->getTree($power_name);
        
        $powerid = $power->powerid ? array_filter($power->powerid) : '';
        
        $data = [
            'info' => $power,
            'dp_name' => $dp_name,
            'power_name' => $power_name,
            'position_rule' => $getTree,
            'powerid' => $powerid,
        ];
        return view("admin.position_view",$data);
    }
    
    public function list(){
        $data['position'] =$a = SysAdminDepartment::with(['position'=>function($query){
            $query->where('status',1);
        }])->where([['status',1]])->get()->keyBy('id');
        $data['department'] = SysAdminDepartment::where([['parent_id',0],['status',1]])
            ->with(['children'=>function($query){
                $query->where('status',1);
            }])->get();
        
        $data['all'] = BaseSysAdminDepartment::all()->keyBy('id');
        
        //权限
        $data['power'] = SysAdminPower::where('parent_id',0)->with('allchild')->get();
        return view('admin.position_list',$data);
    }
    
    public function getListData(){
        $list  =  BaseSysAdminPosition::select('sys_admin_position.*','sys_admin_department.dp_name')
            ->leftjoin('sys_admin_department','sys_admin_department.id','=','sys_admin_position.department_id')->where('sys_admin_position.id','>',0);
        $datatable = DataTables::eloquent($list);
        Log::error("base信息=======".json_encode($datatable));
        return $datatable->make(true);
    }
    
    public function powerName($powerid){
        $power_names ="";
        foreach ($powerid as $k=>$v){
            $power_name =SysAdminPower::find($v);
            $power_names .=$power_name->pname.",";
        }
        return $power_names;
    }
    
    public function save(AdminPositionRequest $request){
        // Log::error("====input===>",json_encode(Input::all()));
        $result =new Result();
        if($request->ajax()) {
            try {
                if($request->id>0){
                    $power  = BaseSysAdminPosition::find($request->id);
                }else{
                    $power  = new BaseSysAdminPosition();
                }
                $power->fill(Input::all());
                $power->powerid = '|';
                $power->save();
                $result->msg = "操作成功";
                $result->code =  Constant::OK;
            } catch (\Exception $e) {
                $result->msg = "操作失败";
            }
        }else{
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
    
    public function update(Request $request){
        $result =new Result();
        if($request->ajax()) {
            try {
                $id = Input::get('id');
                if (!empty($request->powerid) && empty($id)){
                    if (empty($request->position_name)){
                        $result->code=Constant::ERROR;
                        $result->msg="请选择职位";
                        return response()->json($result);
                    }
                }
                $position= new BaseSysAdminPosition();
                if(!empty($id)){
                    if (!empty($request->position_name)){
                        $po_name_arr = $position->where('id','!=',$id)->pluck('position_name')->toArray();
                        if (in_array($request->position_name,$po_name_arr)){
                            $result->msg="职位名称不能重复";
                            return response()->json($result);
                        }
                        if (empty($request->department_id)){
                            $result->code=Constant::ERROR;
                            $result->msg="归属部门不能为空";
                            return response()->json($result);
                        }
                        if (empty($request->desc)){
                            $result->code=Constant::ERROR;
                            $result->msg="职位说明不能为空";
                            return response()->json($result);
                        }
                    }else{
                        if (empty($request->powerid)){
                            $result->code=Constant::ERROR;
                            $result->msg="请正确操作";
                            return response()->json($result);
                        }
                    }
                    $dept  = BaseSysAdminPosition::find($request->id);
                    if(!empty($dept)){
                        $dept->fill($request->all());
                        $dept->save();
                        $result->code=Constant::OK;
                        $result->msg="操作成功";
                    }
                }else{
                    if (!empty($request->position_name)){
                        $po_name_arr = $position->pluck('position_name')->toArray();
                        if (in_array($request->position_name,$po_name_arr)){
                            $result->msg="职位名称不能重复";
                            return response()->json($result);
                        }
                        if (empty($request->department_id)){
                            $result->code=Constant::ERROR;
                            $result->msg="归属部门不能为空";
                            return response()->json($result);
                        }
                        if (empty($request->desc)){
                            $result->code=Constant::ERROR;
                            $result->msg="职位说明不能为空";
                            return response()->json($result);
                        }
                    }else{
                        if (empty($request->powerid)){
                            $result->code=Constant::ERROR;
                            $result->msg="请正确操作";
                            return response()->json($result);
                        }
                    }
                    if (empty($request->position_name) && empty($request->department_id) && empty($request->desc) && empty($request->powerid)){
                        $result->code=Constant::ERROR;
                        $result->msg="请正确操作";
                        return response()->json($result);
                    }
                    $dept = new BaseSysAdminPosition();
                    $dept->fill($request->all());
                    $dept->save();
                    $result->code=Constant::OK;
                    $result->msg="操作成功";
                }
            } catch (\Exception $e) {
                $result->msg = "操作失败";
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
                    BaseSysAdminPosition::find($request->id)->update(['status'=>0]);
                }
                $result->msg = "操作成功";
                $result->code =  Constant::OK;
            } catch (\Exception $e) {
                $result->msg = "操作失败";
            }
        }else{
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
}
