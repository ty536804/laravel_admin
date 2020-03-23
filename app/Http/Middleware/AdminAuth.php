<?php
namespace App\Http\Middleware;

use App\Models\Admin\SysAdminPower;
use App\Services\AdminUser;
use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        $actions =Str::replaceFirst("App\Http\Controllers\\","",\Route::current()->getActionName());
        $adminUser = new AdminUser();
        $uid  =  $adminUser->getId();
        if(!$uid){
            Redirect::guest($request->url());
            return Redirect::to(URL::action("Admin\AdminController@loginViews"));
        }
        $power = SysAdminPower::where('purl',$actions)->first();
//        Log::error("==power=".json_encode($power));
//        Log::error("==userinfo=".json_encode($adminUser->getUser()));
        if(!empty($power)){
            if(!empty($adminUser->getUser())){
                Cache::put('landingRouting', $request->url(), 1);
                if(!Str::contains($adminUser->getUser()->position_power.$adminUser->getUser()->department->powerid,"|".$power->id."|")){
                    Redirect::guest($request->url());
                    return Redirect::to(URL::action("Admin\AdminController@loginViews"));
                }
            }else{
                Log::error("权限和用户信息Session获取失败");
                Redirect::guest($request->url());
                return Redirect::to(URL::action("Admin\AdminController@loginViews"));
            }
        }
        return $next($request);
    }
}