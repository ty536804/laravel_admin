<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\ArticleServices;
use App\Services\BannerServices;
use App\Services\FrontendServices;
use App\Services\SiteServices;
use Illuminate\Support\Facades\Input;

class IndexController extends Controller
{
    //
    protected $frontend;
    protected $article;
    protected $position;
    
    public function __construct(FrontendServices $frontend,ArticleServices $article,BannerServices $position)
    {
        $this->frontend = $frontend;
        $this->article = $article;
        $this->position = $position;
    }
    
    /**
     * @description 首页信息
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function index()
    {
        $site = new SiteServices();
        $data["banner"] = $this->frontend->banner(1);
        $data["essay"] = $this->frontend->essay(1);
        $data['menuList'] = $this->position->menu();
        $data["site"] = $site->siteInfo();
        return view("welcome", $data);
    }
    
    /**
     * @description 关于魔数
     * @auther caoxiaobin
     */
    public function about()
    {
        $data["banner"] = $this->frontend->banner(2);
        $data["essay"] = $this->frontend->essay(2);
        return view("index.about");
    }
    
    /**
     * @description 课程体系
     * @auther caoxiaobin
     */
    public function essay()
    {
        $data["banner"] = $this->frontend->banner(3);
        $data["essay"] = $this->frontend->essay(3);
        return view("index.essay");
    }
    
    /**
     * @description 教研教学
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function learn()
    {
        $data["banner"] = $this->frontend->banner(4);
        $data["essay"] = $this->frontend->essay(4);
        return view("index.essay");
    }
    
    /**
     * @description AI学习平台
     * @auther caoxiaobin
     */
    public function study()
    {
        $data["banner"] = $this->frontend->banner(5);
        $data["essay"] = $this->frontend->essay(5);
        return view("index.study");
    }
    
    /**
     * @description OMO模式
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function omoMode()
    {
        $data["banner"] = $this->frontend->banner(6);
        $data["essay"] = $this->frontend->essay(6);
        return view("index.mode");
    }
    
    /**
     * @description 全国校区
     * @auther caoxiaobin
     */
    public function school()
    {
        $data["banner"] = $this->frontend->banner(7);
        $data["essay"] = $this->frontend->essay(7);
        return view("index.school");
    }
    
    /**
     * @description 魔数动态
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function list()
    {
        $articleList = $this->article->articleList(Input::get("id"));
        return view("index.list", $articleList);
    }
    
    /**
     * @description 魔数动态详情
     * @auther caoxiaobin
     */
    public function detail()
    {
        $detail = $this->article->getOneArticle(Input::get("id"));
        if (!$detail) {
            return back()->with(["内容不存在"]);
        }
        return view("index.detail");
    }
}
