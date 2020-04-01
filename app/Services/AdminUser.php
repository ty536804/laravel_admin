<?php
namespace App\Services;

use App\Models\Admin\SysAdminPower;
use App\Tools\Constant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AdminUser
{
    private $id = 0;
    public $user = "";
    
    /**
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|int|mixed
     */
    public function getId()
    {
        $this->id = Session::get(Constant::ADMIN_SESSION_ID, '0');
        return $this->id;
    }
    
    /**
     * @param \Illuminate\Session\SessionManager|\Illuminate\Session\Store|int|mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
        Session::put(Constant::ADMIN_SESSION_ID, $id);
    }
    
    /**
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed|string
     */
    public function getUser()
    {
        $this->user = session(Constant::ADMIN_SESSION);
        return $this->user;
    }
    
    /**
     * @return \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed|string
     * 获取当前用户的城市 id
     */
    public function getUserCityIds()
    {
        $this->user = session(Constant::ADMIN_SESSION);
        return $this->user->city_id;
    }
    
    /**
     * @param \Illuminate\Session\SessionManager|\Illuminate\Session\Store|mixed|string $user
     */
    public function setUser($user): void
    {
        Session::put(Constant::ADMIN_SESSION, $user);
        $this->user = $user;
    }
    
    public function forget()
    {
        Cache::forget('leftMenu_' . $this->getId());
        session::forget(Constant::ADMIN_SESSION_ID);
        Session::forget(Constant::ADMIN_SESSION);
    }
    
    /**
     * @return mixed
     * @description 缓存用户的左侧菜单永久有效， 根据左侧菜单和点击的Action 更改菜单的选中状态 Active
     */
    public function leftMenu()
    {
        $actions = explode('\\', \Route::current()->getActionName());
        $action = end($actions);
        $leftMenuCache = Cache::remember('leftMenu_' . $this->getId(), 4800, function () {
            $leftMenuCache = null;
            $userInfo = $this->getUser();
            if (is_null($userInfo)) {
                $leftMenuCache = null;
            } else {
                $allpower = SysAdminPower::select('id', 'pname', 'icon', 'purl', 'parent_id', 'pindex')->where('status', 1)->orderBy('pindex', 'asc')->get();
                $leftMenuCache['allpower'] = $allpower;
                $mypower = $userInfo->position_power . $userInfo->department->powerid;
                $routes = $this->getAllroutes();
                foreach ($allpower as $power) {
                    if ($routes->has($power->purl) || $power->purl == "#") {
                        $power->active = "";
                        //构造一个  leftMenu[父节点][子节点] 的数组；  父节点==0 是一级菜单。 父节点非0 二级菜单
                        if (strpos($mypower, 'ALL') !== false) {//含有所有的权限
                            $leftMenuCache[$power->parent_id][$power->id] = $power->toArray();
                        } else {
                            $mypowerid = preg_split("/\\|/", $mypower);
                            foreach ($mypowerid as $id) {
                                if ($power->id == $id) {
                                    $leftMenuCache[$power->parent_id][$power->id] = $power->toArray();
                                }
                            }
                        }
                    }
                }
                //遍历leftMenu 去掉 一级菜单中Url=# 并且没有子节点数据
                $levelOne = $leftMenuCache[0];
                foreach ($levelOne as $k => $v) {
                    if ($v['purl'] == "#" && !array_key_exists($k, $leftMenuCache)) {
                        unset($leftMenuCache[0][$k]);
                    }
                }
            }
            return $leftMenuCache;
        });
        $main_active = 0;
        $sub_action = 0;
        if (is_array($leftMenuCache)) {
            foreach ($leftMenuCache['allpower'] as $power) {
                //dump($power);die;
                if (strpos($power->purl, $action) !== false) { //当前Action 处理 菜单激活
                    $main_active = $power->parent_id;
                    $sub_action = $power->id;
                }
            }
        }
        //只是处理菜单的Active 状态。
        //如果都为0,看Session记录的状态
        if ($main_active == 0 && $sub_action == 0) {
            $main_active = Session::get("ACTIVE_MAINMENU", 0);
            $sub_action = Session::get("ACTIVE_SUBMENU", 0);
        } else {
            Session::put("ACTIVE_MAINMENU", $main_active);
            Session::put("ACTIVE_SUBMENU", $sub_action);
        }
        if ($main_active != 0 && $sub_action != 0) {
            if (array_key_exists($main_active, $leftMenuCache[0])) {
                $leftMenuCache[0][$main_active]['active'] = "active";
            }
            if (array_key_exists($main_active, $leftMenuCache) && array_key_exists($sub_action, $leftMenuCache[$main_active])) {
                $leftMenuCache[$main_active][$sub_action]['active'] = "active";
            }
        }
        unset($leftMenuCache['allpower']);
        return $leftMenuCache;
    }
    
    public function getAllroutes()
    {
        $allroutes = app()->routes->getRoutes();
        $routes = collect();
        foreach ($allroutes as $k => $value) {
            if (isset($value->action['controller'])) {
                $route = collect([
                    'uri'    => $value->uri,
                    'path'   => $value->methods[0],
                    'action' => Str::replaceFirst("App\\Http\\Controllers\\", "", $value->action['controller']),
                ]);
                $routes->push($route);
            }
        }
        return $routes->groupBy('action');
    }
}