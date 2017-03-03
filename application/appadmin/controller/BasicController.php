<?php
namespace app\appadmin\controller;

use think\Controller;
use thinkcms\auth\Auth;

abstract class BasicController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $auth                   = new Auth();
        $auth->noNeedCheckRules = ['index/index/index','index/index/home'];
        $user                   = $auth::is_login();

        if($user){//用户登录状态
            $this->uid = $user['uid'];
            if(!$auth->auth()){
                return $this->error("你没有权限访问！");
            }
        }else{
            return $this->error("您还没有登录！",url("publics/login"));
        }
    }

}