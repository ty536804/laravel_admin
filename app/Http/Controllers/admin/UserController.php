<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    /**
     * @description 修改密码
     * @param Request $request
     * @auther caoxiaobin
     * date: 2020-03-23
     */
    public function change(Request $request) {
        if ($request->ajax()) {
        
        }
    }
    
    
    /**
     * @description 修改用户信息
     * @param Request $request
     * @auther caoxiaobin
     * date: 2020-03-23
     */
    public function updateInfo(Request $request) {
        if ($request->ajax()) {
            dd($request->all());
        }
    }
}
