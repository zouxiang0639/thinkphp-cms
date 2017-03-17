<?php
namespace app\manage\controller;

use think\Cache;
use think\Controller;
use app\common\model\AdminModel;
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

            $validate = [
                ['admin_name|用户名','require|max:25'],
                ['admin_password|密码','require'],
                //[ 'verify|验证码','require|captcha']
            ];

            //数据验证
            $result = $this->validate($post,$validate);
            if (true !== $result) {
                return $this->error($result);
            }

            $login  = AdminModel::login($post);

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