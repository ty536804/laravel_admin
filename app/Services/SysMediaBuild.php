<?php
namespace App\Services;

use App\Models\Base\BaseSysMedia;

class SysMediaBuild
{
    /**
     * @param $ids
     * @return string|static
     * @description 根据IDS得到 数据
     */
    public function  getDataByIds($ids){
        $list  = BaseSysMedia::whereIn('id',explode("|",$ids))
            ->select('id','uid','m_type','m_format','m_name','m_url','m_size','m_width','m_height')
            ->get();
        if($list->count()==0){
            return "";
        }else {
            return $list->keyBy('id');
        }
    }
    public function  getListByIds($ids){
        $list  = BaseSysMedia::whereIn('id',explode("|",$ids))
            ->select('id','uid','m_type','m_format','m_name','m_url','m_size','m_width','m_height')
            ->get();
        return $list;
    }
    public function  getDataByUrls($urls){
        $list  = BaseSysMedia::whereIn('m_url',explode("|",$urls))
            ->select('id','uid','m_type','m_format','m_name','m_url','m_size','m_width','m_height')
            ->get();
        if($list->count()==0){
            return "";
        }else {
            return $list->keyBy('id');
        }
    }
    public function  getListByUrls($urls){
        $list  = BaseSysMedia::whereIn('m_urls',explode("|",$urls))
            ->select('id','uid','m_type','m_format','m_name','m_url','m_size','m_width','m_height')
            ->get();
        return $list;
    }
    /**
     * @param $id
     * @return string
     * @description  通过文件Id获得文件信息
     */
    public  function buildFileDataById($id){
        $list  = BaseSysMedia::where('id',$id)
            ->select('id','uid','m_type','m_format','m_name','m_url','m_width','m_height','m_size')
            ->get();
        if($list->count()==0){
            return "";
        }else{
            return  $list->keyBy('id');
        }
    }
    
    /**
     * @param $url
     * @return string
     * @description  通过文件URL获得文件信息
     */
    public  function buildFileDataByUrl($url){
        $list  = BaseSysMedia::where('m_url',$url)
            ->select('id','uid','m_type','m_format','m_name','m_url','m_width','m_height','m_size')
            ->get();
        if($list->count()==0){
            return "";
        }else{
            return  $list->keyBy('id');
        }
    }
    
    /**
     * @param $info
     * @return string
     * @description  文件信息中解析出来URL
     */
    public function getUrl($info){
        $url = "";
        if(!empty($info)) {
            $info = json_decode($info);
            foreach ($info as $v) {
                $url = $v->m_url;
                break;
            }
        }
        return $url;
    }
    
    /**
     * @param $info
     * @return int
     * @description 文件信息中解析出来ID
     */
    public function getId($info){
        $id ="";
        if(!empty($info)) {
            $info = json_decode($info);
            
            foreach ($info as $v) {
                $id = $v->id;
                break;
            }
        }
        return $id;
    }
    /**
     * @param $info
     * @return array
     * @description 文件信息中解析出来URL 数组
     */
    public function getUrls($info){
        $info  =  json_decode($info);
        $url =array();
        foreach($info as $v){
            array_push($url,$v->m_url);
        }
        return $url;
    }
    public function getUrlsStr($info){
        $url ="";
        if(!empty($info)) {
            $info = json_decode($info);
            foreach ($info as $v) {
                $url .= $v->m_url . "|";
            }
        }
        return $url;
    }
    /**
     * @param $info
     * @return array
     * @description 文件信息中解析出来ID 数组
     */
    public function getIds($info){
        $id =array();
        if(!empty($info)) {
            $info = json_decode($info);
            foreach ($info as $v) {
                array_push($url, $v->id);
            }
        }
        return $id;
    }
    /**
     * @param $info
     * @return string
     * @description 文件信息中解析出来ID 字符串
     */
    public function getIdsStr($info){
        $ids ="";
        if(!empty($info)){
            $info  =  json_decode($info);
            foreach($info as $v){
                $ids.=$v->id."|";
            }
        }
        return $ids;
    }
    /**
     * 查询当前用户所有的图片id
     */
    public function getImgIds($uid){
        $BaseSysMedia =BaseSysMedia::select('id')->where([
            ['uid','=',$uid],
            ['m_status','=',1]
        ])->get()->toArray();
        foreach ($BaseSysMedia as $k=>$v){
            $imgId[] =$v['id'];
        }
        $imgId = "|".implode("|",$imgId)."|";
        return $imgId;
        
    }
}