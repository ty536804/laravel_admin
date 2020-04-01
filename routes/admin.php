<?php
use Illuminate\Support\Facades\Route;

Route::get('/admin', "Admin\AdminController@loginViews", function () {
    return view('admin/login');
});

Route::get('/login', "Admin\AdminController@loginViews", function () {
    return view('admin/login');
});

//不用登陆验证的 登陆注册
Route::group(['namespace' => 'Admin','prefix' => 'admin'], function () {
    Route::get('login', 'AdminController@loginViews');  //后台登陆页面
    Route::post('loginc', 'AdminController@login'); //后台登陆逻辑
    Route::get('logout', 'AdminController@logout')->name('admin.logout');  //退出
});

//后台管理
Route::group(['prefix' => 'admin','namespace' => 'Admin'], function () {
    //用户
    Route::group(['prefix' => 'user'], function () {
        Route::get('view', 'UserController@view');
        Route::get('list', 'UserController@list');
        Route::post('delete', 'UserController@delete');
        Route::post('listdata', 'UserController@getListData');
        Route::post('save', 'UserController@save');
        Route::post('linkage', 'UserController@Linkage');
    
        Route::post("change","UserController@change");
        Route::post("update","UserController@updateInfo");
    });
    //权限
    Route::group(['prefix' => 'power'], function () {
        Route::get('manager','PowerController@manager');
        Route::get('view','PowerController@view');
        Route::get('updateview','PowerController@updateView');
//        Route::get('two-view','PowerController@addTwoMenus');
        Route::get('list','PowerController@list');
        Route::post('listdata','PowerController@getListData');
        Route::post('delete','PowerController@delete');
        Route::post('save','PowerController@save');
        Route::post('search','PowerController@searchUrl');
    });
    //部门
    Route::group(['prefix' => 'dept'], function () {
        Route::get('list','DepartmentController@list');
        Route::post('update','DepartmentController@update');
        Route::post('delete','DepartmentController@delete');
        Route::post('save','DepartmentController@save');
        Route::get('powertreedata','DepartmentController@powerTreeData');
        Route::post('save','DepartmentController@save');
        
    });
    
    //职位
    Route::group(['prefix' => 'posi'], function () {
        Route::get('view','PositionController@view');
        Route::post('update','PositionController@update');//职位添加/修改
        Route::get('list1','PositionController@list1');
        Route::get('list','PositionController@list');
        Route::post('listdata','PositionController@getListData');
        Route::post('save','PositionController@save');
        Route::post('add','PositionController@positionAdd');
    });
    //管理员资料管理
//    Route::group(['prefix' => 'self'], function () {
//        Route::get('set','UserController@set');
//        Route::post('save','UserController@set_save');
//    });
//
    Route::group(['prefix' => 'site'], function () {
        Route::get('show','SiteController@show');
        Route::post('save','SiteController@siteSave');
    });
});
//首页管理
Route::group(['middleware'=>'adminAuth','namespace' => 'Admin','prefix' => 'admin'], function (){
    Route::any('admin','AdminController@index');
    Route::get('index','AdminController@index');//首页
    Route::get('main','AdminController@main'); //首页body展示
    Route::get('cs','AdminController@leftMenu'); //测试代码
});

//文件上传
Route::group(['prefix'=>'files'],function(){
    Route::post('upload','FileInputUploadController@anyUpload');
    Route::post('del','FileInputUploadController@delete');
    Route::get('download/{id}','FileInputUploadController@download');
});