<?php
namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ArticleRequest;
use App\Models\Backend\Article;
use App\Tools\ApiResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class ArticleController extends Controller
{
    //
    use ApiResult;
    
    /**
     * @description 文章首页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-26
     */
    public function show() {
        return view("article.index");
    }
    
    /**
     * @description 文章列表
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * @auther caoxiaobin
     * date: 2020-03-26
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
     * date: 2020-03-26
     */
    public function articleSave(ArticleRequest $request)
    {
        if ($request->ajax()) {
            $id= $request->post("id",0);
            if ($id < 1) {
                $article = new Article();
            } else {
                $article = Article::find($id);
            }
            $article->fill($request->all());
            if ($article->save()) {
                return $this->success("操作成功");
            } else {
                return $this->error("操作失败");
            }
        }
        return $this->error("操作失败");
    }
    
    /**
     * @description 添加文章详情页面
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @auther caoxiaobin
     * date: 2020-03-26
     */
    public function articleDetail() {
        $id = Input::get("id");
        if ($id < 1) {
            $article = Article::find($id);
            if (!$article) {
                return back()->withErrors("详情页面不存在");
            }
        }
        return view("article.detail");
    }
}
