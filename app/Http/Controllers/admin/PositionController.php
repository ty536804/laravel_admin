<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPositionRequest;
use App\Models\Admin\SysAdminDepartment;
use App\Models\Admin\SysAdminPosition;
use App\Models\Admin\SysAdminPower;
use App\Models\Base\BaseSysAdminDepartment;
use App\Models\Base\BaseSysAdminPosition;
use App\Services\AdminUser;
use App\Tools\ApiResult;
use App\Tools\Constant;
use App\Tools\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller
{
    //
    use ApiResult;
    
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
    
    /**
     * @description 职位列表
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function list(){
        $position = BaseSysAdminPosition::all()->keyBy('id');
        $department = SysAdminDepartment::where('parent_id',0)->with('children')->get();
        $all = BaseSysAdminDepartment::all()->keyBy('id');
        //权限
        $power = SysAdminPower::where('parent_id',0)->with('allchild')->get();
        return view('admin.position_list',['position'=>$position,'department'=>$department,'power'=>$power,'all'=>$all]);
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
                $power->fill($request->all());
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
    
    /**
     * @description 添加职位
     * @param AdminPositionRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function update(AdminPositionRequest $request){
        $result =new Result();
        if($request->ajax()) {
            if (SysAdminPosition::where("position_name", $request->post("position_name"))->count()>1) {
                return $this->error("职位名称不允许重复");
            }
            try {
                $id = $request->get('id',0);
                if ($id < 1) {
                    $dept = new BaseSysAdminPosition();
                } else {
                    $dept = SysAdminPosition::find($id);
                }
                $request["powerid"]= "|";
                $dept->fill($request->all());
                $dept->save();
                $result->code=Constant::OK;
                $result->msg="操作成功";
            } catch (\Exception $e) {
                $result->msg = "操作失败";
                Log::info("职位添加失败",[$e->getMessage()]);
                $result->code=Constant::ERROR;
            }
        }else{
            $result->code=Constant::ERROR;
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
    
    /**
     * @description 职位权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function positionAdd(Request $request)
    {
        if ($request->ajax()) {
            $id = $request->post("id", 0);
            if ($id < 1) {
                return $this->error("选择职位");
            }
            $powerid = $request->post("powerid","");
            if (empty($powerid)) {
                return $this->error("选择职位");
            }
            
            $dept = SysAdminPosition::find($id);
            if (!$dept) {
                return $this->error("当前职位已删除，刷新页面");
            }
            $dept->powerid = $powerid;
            if ($dept->save()) {
                return $this->success("操作成功");
            } else {
                return $this->error("操作失败");
            }
        } else {
            return $this->error("非法操作");
        }
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
