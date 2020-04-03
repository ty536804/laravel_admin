<?php
namespace App\Services;

use App\Models\Admin\SysAdminUser;
use App\Tools\ApiResult;
use App\Tools\Result;

class UserServices
{
    use ApiResult;
    
    private $result;
    
    public function __construct()
    {
        $this->result = new Result();
    }
    
    /**
     * @description 修改用户信息
     * @param $data
     * @return Result
     * @auther caoxiaobin
     */
    public function updateInfo($data) {
        
        $login_name = $data['login_name'] ?? '';
        $email = $data['email'] ?? '';
        $tel = $data['tel'] ?? '';
    
        $id = $data['id'] ?? 0;
        if ($id <1) {
            return $this->error("非法请求");
        }
        
        if (empty(trim($login_name))) {
            return $this->error("用户名不能为空");
        }
        
        if (empty(trim($email))) {
            return $this->error("邮箱不能为空");
        }
    
        if (empty($tel) || !$this->checkMobile($tel)) {
            return $this->error("手机号格式不正确");
        }
        
        $adminUser = $this->getUserInfo($id);
        if (!$adminUser) {
            return $this->error("用户不存在");
        }
        
        $adminUser->fill($data);
        if ($adminUser->save()) {
            $AdminUser = new AdminUser();
            $AdminUser->setUser($adminUser);
            return $this->success("修改成功");
        } else {
            return $this->error("修改失败");
        }
    }
    
    /**
     * @description 修改密码
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     * @auther caoxiaobin
     * date: 2020-03-24
     */
    public function editPwd($data) {
        $id = $data['id'] ?? 0;
        $oldPWD = $data['pwd'] ?? '';
        $newPWD = $data['newpwd'] ?? '';
        
        if (empty(trim($oldPWD))) {
            return $this->error("旧密码不允许为空");
        }
        
        if (empty($newPWD) || strlen(trim($newPWD)) <6) {
            return $this->error("新密码不允许为空,且不允许小于6");
        }
    
        $userInfo = $this->getUserInfo($id);
        if (!$userInfo) {
            return $this->error("用户不存在");
        }
        
        if ($userInfo->pwd != md5(trim($oldPWD))) {
            return $this->error("原始密码密码不正确");
        }
        $data['pwd'] = md5(trim($oldPWD));
        $userInfo->fill($data);
        if ($userInfo->save()) {
            $AdminUser = new AdminUser();
            $AdminUser->forget();
            return $this->success("修改成功");
        } else {
            return $this->error("修改失败");
        }
    }
    
    /**
     * @description 获取用户信息
     * @param $id
     * @return mixed
     * @auther caoxiaobin
     * date: 2020-03-24
     */
    public function getUserInfo($id) {
        return SysAdminUser::find($id);
    }
}