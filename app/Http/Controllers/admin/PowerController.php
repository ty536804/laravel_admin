<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminPowerRequest;
use App\Models\Admin\SysAdminPower;
use App\Services\AdminUser;
use App\Tools\Constant;
use App\Tools\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class PowerController extends Controller
{
    //
    public $adminUser;
    function __construct(AdminUser $user) {
        $this->adminUser =  $user;
    }
    
    public function view(Request $request) {
        $id=$request->get('id',0);
        if ($id!=0) {
            $power = SysAdminPower::find($id);
        } else {
            $power = new SysAdminPower();
            if ($request->pid > 0){
                $power->parent_id = $request->pid;
                $data['pname'] =SysAdminPower::select('id','pname')->where('parent_id',0)->get();
            }
        }
        $data['id']=$id;
        $data['pname'] =SysAdminPower::select('id','pname')->where('parent_id',0)->get();
        $data['info']=$power;
        return view("admin.power_view",$data);
    }
    public function updateView(Request $request) {
        $id=$request->get('id',0);
        $power = SysAdminPower::find($id);
        $data['pid']=$power->parent_id;
        $data['pname'] = SysAdminPower::select('id','pname')->where('parent_id',0)->get();
        $data['info']=$power;
        return view("admin.power_update_view",$data);
    }
    
    public function manager(Request $request) {
        $data['list']=SysAdminPower::where("parent_id",0)
            ->with('allchild')
            ->get();
        $data['data'] =  SysAdminPower::where('id',2)
            ->select("id","pname","ptype","desc")->get();
        Log::error(json_encode($data['data']));
        return view("admin.power_manager",$data);
    }
    
    public function list(Request $request) {
        $id=$request->get('id',0);
        if($id!=0){
            $power = SysAdminPower::find($id);
        }else{
            $power = new SysAdminPower();
        }
        $data['pname'] =SysAdminPower::select('id','pname')->where('parent_id',0)->get();
        $data['info']=$power;
        return view("admin.power_list",$data);
    }
    
    public function getListData() {
        $list  =  SysAdminPower::where('id','>',0);
        $datatable = DataTables::eloquent($list);
        return $datatable->make(true);
    }
    
    /**
     * @description 添加权限
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function save(Request $request){
        $result =new Result();
        if ($request->ajax()) {
            try {
                if ($request->post('id')>0) {
                    $power  = SysAdminPower::find($request->id);
                } else {
                    $power  = new SysAdminPower();
                }
                $power->fill($request->all());
                $power->save();
                $result->msg = "操作成功";
                $result->code =  Constant::OK;
            } catch (\Exception $e) {
                Log::info("权限添加失败",$e->getMessage());
                $result->msg = "操作失败";
            }
        } else {
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
    
    /**
     * @description
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function delete(Request $request){
        $result =new Result();
        if ($request->ajax()) {
            try {
                if($request->id>0){
                    $parent_id = SysAdminPower::select('parent_id')->where('parent_id',$request->id)->get()->toArray();
                    if (!$parent_id){
                        SysAdminPower::find($request->id)->update(['status'=>$request->status]);
                        $result->msg = "操作成功";
                        $result->code =  Constant::OK;
                    }else{
                        $result->msg = "有子级不可删除";
                        $result->code =  Constant::ERROR;
                    }
                    
                }
                
            } catch (\Exception $e) {
                $result->msg = "操作失败";
            }
        } else {
            $result->msg  = "Invalid Request";
        }
        return response()->json($result);
    }
}
