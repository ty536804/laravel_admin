<?php
namespace App\Models\Admin;

use App\Models\Base\BaseSysAdminPower;

class SysAdminPower extends BaseSysAdminPower
{
    public function child(){
        return $this->hasMany(SysAdminPower::class,'parent_id','id');
    }
    
    public function allchild()
    {
        return $this->child()->with('allchild');
    }
    
    public function parent(){
        return $this->hasOne(SysAdminPower::class ,'id','parent_id');
    }
    
    public function myparent()
    {
        return $this->parent()->with('myparent');
    }
    
    public static function getPathName($id){
        $info  =  SysAdminPower::where('id',$id)->with('myparent')->first();
        $pathName = "";
        self::getParent($info,$pathName);
        return $pathName;
    }
    private static  function  getParent($info,&$name){
        if(!empty($info->myparent)){
            $name=$info->cname."/".$name;
            self::getParent($info->myparent,$name);
        }else{
            $name=$info->cname."/".$name;
        }
        return $name;
    }
    
    public static function  getAllChildId($info,&$ids){
        if(!empty($info->allchild) && count($info->allchild)!=0 ){
            $ids.=$info->id."|";
            foreach($info->allchild as $v){
                self::getAllChildId($v,$ids);
            }
        }else{
            $ids.=$info->id."|";
        }
        return $ids;
    }
    
    
    
    public function getTree($data, $pId=0) {
        $tree = [];
        foreach($data as $k => $v) {
            if($v->parent_id == $pId) {
                $childs = $this->getTree($data, $v->id);
                $v = [
                    'id' => $v->id,
                    'pname' => $v->pname,
                    'pid' => $v->parent_id,
                    'child'=> $childs
                ];
                $tree[] = $v;
            }
        }
        return $tree;
    }
}//Created at 2020-03-23 03:33:41