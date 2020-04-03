<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ArticleRequest;
use App\Models\Backend\Article;
use App\Services\ArticleServices;
use App\Tools\ApiResult;
use App\Tools\Constant;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    //
    use ApiResult;
    protected $article;
    
    public function __construct(ArticleServices $article)
    {
        $this->article = $article;
    }
    
    /**
     * @description 文章首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-26
     */
    public function show()
    {
        return view("article.index");
    }
    
    /**
     * @description 文章列表
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @auther caoxiaobin
     */
    public function articleList() {
        $list = Article::where('id','>',0);
        $database = DataTables::eloquent($list);
        return $database->make(true);
    }
    
    /**
     * @description 添加/编辑 文章
     * @param ArticleRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function articleSave(ArticleRequest $request)
    {
        if ($request->ajax()) {
            return $this->article->articleSave($request->all());
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description 添加文章详情页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function articleDetail() {
        $result = $this->article->articleDetail(Input::get("id"));
        if ($result->code == Constant::ERROR) {
            return back()->withErrors("内容不存在");
        }
        return view("article.detail", $result->data);
    }
    
    /**
     * @description 首页
     * @auther caoxiaobin
     */
    public function articleShow()
    {
        return view("article.show");
    }
    
    /**
     * @description 首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     */
    public function articleAdd()
    {
        return view("article.show");
    }
}
