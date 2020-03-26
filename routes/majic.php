<?php
use Illuminate\Support\Facades\Route;

Route::group(["namespace"=>"Backend","prefix"=>"backend"],function () {
    Route::get("show","BannerController@index");//banner列表页面
    Route::post("list","BannerController@bannerList");//banner请求ajax
    Route::post("del","BannerController@bannerDel");//banner删除
    Route::post("save","BannerController@bannerSave");//banner保存
    Route::get("detail","BannerController@bannerDetail");//banner详情页
    Route::get("positionList","BannerController@positionList");//轮播图展示位置
    Route::post("positionData","BannerController@positionData");//轮播图展示位置ajax
    Route::post("positionDel","BannerController@positionDel");//删除轮播图展示位置
    Route::post("positionEdit","BannerController@positionEdit");//轮播图位置展示编辑
    Route::post("positionSave","BannerController@positionSave");//轮播图位置展示保存
});


Route::group(["namespace"=>"Backend","prefix"=>"backend"],function () {
    Route::get("show","MessageController@index");//留言列表页面
    Route::get("detail","MessageController@messageDetail");//留言列表页面
    Route::post("list","MessageController@messageList");//留言列表ajax
    Route::post("save","MessageController@messageSave");//提交留言
});

Route::group(["namespace"=>"Backend","prefix"=>"backend"],function () {
    Route::get("show","ArticleController@index");//文章列表页面
    Route::get("detail","ArticleController@articleDetail");//文章页面
    Route::post("list","ArticleController@articleList");//文章列表ajax
    Route::post("save","ArticleController@articleSave");//提交文章
});