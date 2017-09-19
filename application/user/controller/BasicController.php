<?php
namespace app\user\controller;

use think\Controller;
use think\Request;
use app\common\library\request\RequestHook;

class BasicController extends Controller
{
    public $user_id;

    public function __construct()
    {
        //Request注入方法
        RequestHook::run();

        parent::__construct();

        if(! $this->request->is_login()) {
            return $this->redirect('portal/login');
        }

        $this->user_id = $this->request->getUser()->user_id;

        $this->assign('leftMenu', self::leftMenu());
    }

    public function leftMenu()
    {
        return [
            ['id'=>1, 'title' => '会员中心', 'url' => url('index/index'), 'icon' => '&#xe612;'],
            ['id'=>2, 'title' => '基本设置', 'url' => url('setting/profile'), 'icon' => '&#xe620;'],
            ['id'=>3, 'title' => '购物车', 'url' => url('cart/index'), 'icon' => '&#xe698;'],
        ];
    }
}