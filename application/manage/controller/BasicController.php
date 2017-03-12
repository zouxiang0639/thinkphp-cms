<?php
namespace app\manage\controller;

use think\Config;
use think\Controller;
use thinkcms\auth\Auth;

abstract class BasicController extends Controller
{
    public static $uid;

    public function __construct()
    {
        parent::__construct();
        $auth                   = new Auth();
        $auth->noNeedCheckRules = ['manage/index/index','appadmin/index/home'];
        $user                   = $auth::is_login();

        if($user){//用户登录状态
            self::$uid = $user['uid'];
            if(!$auth->auth()){
                return $this->error("你没有权限访问！");
            }
        }else{
            return $this->error("您还没有登录！",url("publics/login"));
        }
    }

    /**
     * 头部菜单
     * @access public
     * @param array     $data
     * @param bool      $scene
     * @return string
     */
    public function navTabs($data,$scene = false)
    {
        //开启场景模式
        if($scene){
            $sceneData  = [];
            foreach($data as $k=>$v){
                $sceneName  = explode("|",$k);
                if(in_array($action,$sceneName)){
                    $sceneData = array_merge($sceneData,$v);
                }
            }
            $data  = $sceneData;
        }

        //生成菜单html
        $HtmlBuilder = new \think\form\HtmlBuilder();
        $nav        = ['nav'=>''];
        $controller = $this->request->module();
        $dispatch   = $this->request->dispatch();
        $path       = strtolower(implode('/',$dispatch['module']));

        foreach($data as $k=>$v){

            //路由匹配
            $url = $v['url'];
            if(is_array($url)){
                $urls        = url($url[0],$url[1]);
                $v['url']   = $url[0];
            }else{
                $urls        = url($url);
            }
            $navPath = strtolower("{$controller}/{$v['url']}");

            //生成html
            if($path == $navPath){
                unset($v['url'],$v['style']);
                $options = $HtmlBuilder->attributes(array_merge($v,['class'=>'active']));
                $nav['title'] = $k;
            }else{
                unset($v['url']);
                $options = $HtmlBuilder->attributes($v);
            }
            $nav['nav'] .="<li{$options}><a href='{$urls}'>{$k}</a></li>";

        }
        return $nav;

    }

}