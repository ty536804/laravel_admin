<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', "Frontend\IndexController@index", function () {
    return view('welcome');
});

Route::group(["namespace"=>"Frontend","prefix"=>"frontend"], function () {
    Route::get("index","IndexController@index");//首页
    Route::get("about","IndexController@about");//关于
    Route::get("essay","IndexController@essay");//课程体系
    Route::get("learn","IndexController@learn");//教研教学
    Route::get("study","IndexController@study");//AI学习平台
    Route::get("omoMode","IndexController@omoMode");//OMO模式
    Route::get("school","IndexController@school");//全国校区
    Route::get("list","IndexController@list");//魔数动态
    Route::get("detail","IndexController@detail");//魔数动态详情
});
