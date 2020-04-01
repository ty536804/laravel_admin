<?php
namespace App\Services;

use App\Models\Base\BaseSysAreacode;
use App\Models\Base\BaseSysMedia;
use App\Tools\Constant;

class AdminMethod extends Constant {
    /**
     * 获取图片的所有值
     */
    public function getImgDefault($img_url) {
        $imgDefault =[];
        if (!empty($img_url)){
            $img_url_arr = explode('|',$img_url);
            foreach ($img_url_arr as $v){
                $yuanshi = BaseSysMedia::where('m_url',$v)->first();
                if ($yuanshi){
                    $imgDefault["$yuanshi->id"] = [
                        "m_width"=>$yuanshi->m_width,
                        "m_height"=>$yuanshi->m_height,
                        "uid"=>$yuanshi->uid,
                        "m_name"=>$yuanshi->m_name,
                        "m_url"=>$yuanshi->m_url,
                        "m_type"=>$yuanshi->m_type,
                        "m_format"=>$yuanshi->m_format,
                        "m_size"=>$yuanshi->m_size,
                        "id"=>$yuanshi->id,
                    ];
                }
            }
        }
        return json_encode($imgDefault);
    }
    
    /**
     * @name 一天中 所有刻
     * @return array
     * @example 00:15 00:30 00:45 01:00
     */
    public function moment($begin = "2018-01-01 00:00:00",$end = "2018-01-01 24:00:00"){
        $begintime = strtotime($begin);
        $endtime = strtotime($end);
        for ($start = $begintime; $start <= $endtime; $start += 900) {
            $moment[] = date("H:i", $start);
            unset($moment[0]);
            unset($moment[96]);
        }
        return $moment;
    }
    
    
    /**
     * 获取所有已开通的城市id 包含全国
     */
    public function cityNameAll() {
        $provinces = BaseSysAreacode::where([['parent_id',-1],['a_status','=','1']])->select('aid','aname')->get()->toArray();
        $data = collect();
        $data->push([
            'aid'=>10000,
            'aname'=>'全国'
        ]);
        if(!empty($provinces)) {
            $cites = BaseSysAreacode::where('a_status',1)->whereIn('parent_id',array_column($provinces,'aid'))->select('parent_id','aname','aid')->get()->toArray();
            foreach ($provinces as $province) {
                foreach ($cites as $city){
                    if($city['parent_id'] == $province['aid']){
                        $data->push($city);
                    }
                }
            }
        }
        return $data->toArray();
    }
    public function cityNameAllJson() {
        $city= $this->cityNameAll();
        $city_list =[];
        foreach ($city as $k=>$v){
            $city_list[$v['aname']] =$v['aid'];
        }
        $city_names = json_encode(array_flip($city_list),JSON_UNESCAPED_UNICODE);
        return $city_names;
    }
    /**
     * 获取所有已开通的城市id
     */
    public function cityName() {
        $provinces = BaseSysAreacode::where([['parent_id',-1],['a_status','=','1']])->select('aid','aname')->get()->toArray();
        $data = collect();
        if(!empty($provinces)) {
            $cites = BaseSysAreacode::where('a_status',1)->whereIn('parent_id',array_column($provinces,'aid'))->select('parent_id','aname','aid')->get()->toArray();
            foreach ($provinces as $province) {
                foreach ($cites as $city){
                    if($city['parent_id'] == $province['aid']){
                        $data->push($city);
                    }
                }
            }
        }
        return $data->toArray();
    }
    /**
     * 获取所有的城市id
     */
    public function citysNames() {
        $provinces = BaseSysAreacode::where([['parent_id',-1]])->select('aid','aname')->get()->toArray();
        $data = collect();
        if(!empty($provinces)) {
            $cites = BaseSysAreacode::whereIn('parent_id',array_column($provinces,'aid'))->select('parent_id','aname','aid')->get()->toArray();
            foreach ($provinces as $province) {
                foreach ($cites as $city){
                    if($city['parent_id'] == $province['aid']){
                        $data->push($city);
                    }
                }
            }
        }
        return $data->toArray();
    }
    /**
     * 获取所有的城市id 名称
     */
    public function citysIdNames() {
        foreach ($this->cityNameAll() as $v){
            $res[$v['aid']] =$v['aname'];
        }
        return $res;
    }
    
    /**
     * start  开始时间
     * end    结束时间
     * 获取开始到结束时间 中间的所有月份
     */
    function showMonthRange($start, $end)
    {
        $end = date('Ym', strtotime($end));
        $range = [];
        $i = 0;
        do {
            $month = date('Ym', strtotime($start . ' + ' . $i . ' month'));
            $range[] = $month;
            $i++;
        } while ($month < $end);
        return $range;
    }
    
    
    /**
     * 查询数组中所有的子级
     * $array 需要传输的的数组
     * $parent_id 从父id 0开始
     */
    public function get_categories_tree_array($array,$parent_id=0,$new_array=[]){
        foreach ($array as $key=>$val){
            if($val->parent_id==$parent_id){
                $new_array[]=$val;
                if(!($val->service)){
                    $new_array[count($new_array)-1]['service']=collect();
                }
                unset($array[$key]);
                $this->get_categories_tree_array($array,$val['id'],$new_array[count($new_array)-1]['service']);
            }
        }
        return ($new_array);
    }
    /**
     * 获取当前用户的城市
     * All 是全国
     */
    public function getAdminCity(){
        $city =new AdminUser();
        $city = explode(',',$city->getUser()->city_id);
        if (in_array(10000,$city)){
            $city_id = $this->cityName();
        }else{
            $city_id = BaseSysAreacode::whereIn('aid',$city)->select('aid','aname','parent_id')->get()->toArray();
        }
        return $city_id;
    }
    public function getAdminCityAll(){
        $city =new AdminUser();
        $city = explode(',',$city->getUser()->city_id);
        
        if (in_array(10000,$city)){
            $city_id = $this->cityNameAll();
        }else{
            $city_id = BaseSysAreacode::whereIn('aid',$city)->select('aid','aname','parent_id')->get()->toArray();
        }
        return $city_id;
    }
    
    /**
     * @name 计算时间差
     * @param $startdate
     * @param $enddate
     * @return float|string
     * @val 返回 列 1天1小时1分钟
     * $val $startdate 开始时间   $enddate 结束时间
     */
    function timeDifference($startdate,$enddate){
        if (empty($startdate) || empty($startdate) || ($enddate < $startdate)){
            return "错误时间";
        }
        $date=floor((strtotime($enddate)-strtotime($startdate))/86400);
        $hour=floor((strtotime($enddate)-strtotime($startdate))%86400/3600);
        $minute=floor((strtotime($enddate)-strtotime($startdate))%86400/60%60);
//        $second=floor((strtotime($enddate)-strtotime($startdate))%86400%60);
        $date = $date."天".$hour."小时".$minute."分钟";
        return $date;
    }
}