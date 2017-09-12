<?php
namespace app\manage\controller;

use app\common\bls\admin\AdminBls;
use think\Cache;
use think\Controller;
use thinkcms\auth\Auth;

class Publics extends Controller
{

    /**
     * 后台登录
     */
    public function login()
    {
        if($this->request->isPost()){
            $post   = $this->request->post();

            //数据验证
            $result = $this->validate($post, 'app\common\bls\admin\validate\AdminValidate.login');
            if(true !== $result){
                // 验证失败 输出错误信息
                return $this->error($result);
            }

            $login  = AdminBls::login($post);

            if($login === false){
                return $this->error('用户名或者密码错误');
            }else{
                //写入session
                Auth::login($login['admin_id'],$login['admin_name']);
                $ip = empty($this->request->ip(1)) ? $this->request->ip(1) : 0 ;
                //记录最后登陆数据
                $update =[
                    'last_login_ip'     => $ip,
                    'last_login_time'   => date('Y-m-d H:i:s')
                ];
                $login->save($update);

                //手动加入日志
                $auth = new Auth();
                $auth->createLog('管理员 {admin_name} 进入后台了,','后台登录');

                return $this->redirect('index/index');
            }
        }
        return $this->fetch();
    }

    /**
     * 退出视图
     */
    public function logout()
    {
        Auth::logout();
        $this->redirect('publics/login');
    }

    /**
     * 清空缓存
     */
    public function clear()
    {
        Cache::clear();
        echo '缓存清除成功';
    }
}