<?php
use Illuminate\Support\Facades\Route;

//不用登陆验证的 登陆注册
Route::group(['namespace' => 'Admin','prefix' => 'admin'], function () {
    Route::get('login', 'AdminController@loginViews');  //后台登陆页面
    Route::post('loginc', 'AdminController@login'); //后台登陆逻辑
    Route::get('logout', 'AdminController@logout')->name('admin.logout');  //退出
});

//首页管理
Route::group(['middleware'=>'adminAuth','namespace' => 'Admin','prefix' => 'admin'], function (){
    Route::any('admin','AdminController@index');
    Route::get('index','AdminController@index');//首页
    Route::get('main','AdminController@main'); //首页body展示
});

Route::group(['middleware'=>'adminAuth','namespace' => 'Admin','prefix' => 'admin'], function (){
    Route::post('update','UserController@updateInfo');
    Route::post('change','UserController@change');
});