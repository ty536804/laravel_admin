<?php
namespace App\Services;

use App\Models\Backend\Article;
use App\Tools\ApiResult;
use App\Tools\Constant;
use App\Tools\Result;

class ArticleServices
{
    use ApiResult;
    
    protected $result;
    protected $admin;
    public function __construct(AdminUser $admin)
    {
        $this->result = new Result();
        $this->admin = $admin;
    }
    
    /**
     * @description 编辑内容
     * @param $request
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     */
    public function articleSave($request)
    {
        $id = $request['id'] ?? 0;
        if ($id < 1) {
            $article = new Article();
        } else {
            $article = $this->getOneArticle($id);
            if (!$article) {
                return $this->error("内容不存在");
            }
        }
        $thumb_img = $request["thumb_img_info"] ?? "";
        if (!empty($thumb_img)) {
            $picInfo = json_decode($thumb_img,true);
            $picInfo = reset($picInfo);
            $request['thumb_img'] = $picInfo['m_url'];
        }
        $article->fill($request);
        if ($article->save()) {
            return $this->success("操作成功");
        } else {
            return $this->error("操作失败");
        }
    }
    
    /**
     * @description 详情
     * @param $id 文章ID
     * @return Result 返回结果
     * @auther xiaobin
     */
    public function articleDetail($id)
    {
        if ($id >=1) {
            $article = $this->getOneArticle($id);
            if (!$article) {
                $this->result->code = Constant::ERROR;
                $this->result->msg = "操作失败";
                return $this->result;
            }
        } else {
            $article = new Article();
        }
        
        $data['info'] = $article;
        $data['admin_id'] = $this->admin->getId();
        $this->result->msg = "操作成功";
        $this->result->code = Constant::OK;
        $this->result->data = $data;
        return $this->result;
    }
    
    /**
     * @description 获取一条信息
     * @param $id 文章ID
     * @return mixed
     * @auther caoxiaobin
     */
    public function getOneArticle($id)
    {
        return Article::find($id);
    }
    
    /**
     * @description 文章列表
     * @param $page 当前页数
     * @return float|int
     * @auther caoxiaobin
     */
    public function articleList($page)
    {
        $pageInfo = $this->pageInfo($page);
        $pageInfo["info"] = Article::where("is_show", 1)
            ->limit(reset($pageInfo))
            ->offset(next($pageInfo))
            ->orderBy("hot", "desc")
            ->orderBy("sort","asc")->get();
        return $pageInfo;
    }
    
    /**
     * @description 获取偏移数
     * @param $page 当前页数
     * @return float|int
     * @auther caoxiaobin
     */
    public function pageInfo($page)
    {
        $page == $page <=1 ? 1 : $page;
        $data['pageSize'] = Constant::PAGE_SIZE;
        $data["offset"] = ($page-1)*Constant::PAGE_SIZE;
        $count = Article::where("is_show", 1)->count();
        $data["count"] = $count;
        return $data;
    }
}